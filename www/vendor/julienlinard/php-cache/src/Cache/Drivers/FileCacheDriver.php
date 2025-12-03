<?php

namespace JulienLinard\Cache\Drivers;

use JulienLinard\Cache\Exceptions\DriverException;

/**
 * Driver de cache sur disque (File)
 * Stocke les données dans des fichiers sur le système de fichiers
 */
class FileCacheDriver extends AbstractCacheDriver
{
    /**
     * Chemin du répertoire de cache
     */
    private string $cachePath;

    /**
     * Permissions des fichiers créés (octal)
     */
    private int $filePermissions = 0644;

    /**
     * Permissions des répertoires créés (octal)
     */
    private int $directoryPermissions = 0755;

    /**
     * Constructeur
     *
     * @param array $config Configuration du driver
     * @throws DriverException Si le répertoire de cache n'est pas accessible
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->cachePath = $config['path'] ?? sys_get_temp_dir() . '/php-cache';
        $this->filePermissions = $config['file_permissions'] ?? 0644;
        $this->directoryPermissions = $config['directory_permissions'] ?? 0755;

        // Créer le répertoire s'il n'existe pas
        if (!is_dir($this->cachePath)) {
            if (!mkdir($this->cachePath, $this->directoryPermissions, true)) {
                throw new DriverException('file', "Impossible de créer le répertoire de cache: {$this->cachePath}");
            }
        }

        // Vérifier les permissions d'écriture
        if (!is_writable($this->cachePath)) {
            throw new DriverException('file', "Le répertoire de cache n'est pas accessible en écriture: {$this->cachePath}");
        }
    }

    /**
     * Génère le chemin du fichier de cache pour une clé
     *
     * @param string $key Clé du cache
     * @return string Chemin du fichier
     */
    private function getFilePath(string $key): string
    {
        // Utiliser le hash de la clé pour éviter les problèmes de caractères spéciaux
        $hash = hash('sha256', $key);
        
        // Créer une structure de répertoires basée sur le hash pour éviter trop de fichiers dans un seul dossier
        $subDir = substr($hash, 0, 2);
        $subDir2 = substr($hash, 2, 2);
        
        $dir = $this->cachePath . '/' . $subDir . '/' . $subDir2;
        
        // Créer le répertoire s'il n'existe pas
        if (!is_dir($dir)) {
            mkdir($dir, $this->directoryPermissions, true);
        }
        
        return $dir . '/' . $hash . '.cache';
    }

    /**
     * Récupère une valeur du cache
     *
     * @param string $key Clé du cache
     * @param mixed $default Valeur par défaut si la clé n'existe pas
     * @return mixed Valeur en cache ou valeur par défaut
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $preparedKey = $this->prepareKey($key);
        $filePath = $this->getFilePath($preparedKey);

        if (!file_exists($filePath)) {
            return $default;
        }

        try {
            $content = file_get_contents($filePath);
            if ($content === false) {
                return $default;
            }

            $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

            // Vérifier l'expiration
            if (isset($data['expires']) && $data['expires'] !== null && $data['expires'] < time()) {
                $this->delete($key);
                return $default;
            }

            return $this->unserializeValue($data['value'] ?? '');
        } catch (\Throwable $e) {
            // En cas d'erreur, supprimer le fichier corrompu
            @unlink($filePath);
            return $default;
        }
    }

    /**
     * Stocke une valeur dans le cache
     *
     * @param string $key Clé du cache
     * @param mixed $value Valeur à stocker
     * @param int|null $ttl Time to live en secondes (null = pas d'expiration)
     * @return bool True si succès, false sinon
     */
    public function set(string $key, mixed $value, ?int $ttl = null): bool
    {
        try {
            $preparedKey = $this->prepareKey($key);
            $filePath = $this->getFilePath($preparedKey);
            $serialized = $this->serializeValue($value);

            // Utiliser le TTL fourni ou le TTL par défaut
            $expires = null;
            if ($ttl !== null) {
                $expires = time() + $ttl;
            } elseif ($this->defaultTtl !== null) {
                $expires = time() + $this->defaultTtl;
            }

            $data = [
                'value' => $serialized,
                'expires' => $expires,
                'created_at' => time(),
            ];

            // Écrire dans un fichier temporaire puis renommer (atomicité)
            $tempFile = $filePath . '.' . uniqid('', true) . '.tmp';
            
            if (file_put_contents($tempFile, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) === false) {
                return false;
            }

            // Renommer de manière atomique
            if (!rename($tempFile, $filePath)) {
                @unlink($tempFile);
                return false;
            }

            // Définir les permissions
            chmod($filePath, $this->filePermissions);

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Supprime une valeur du cache
     *
     * @param string $key Clé du cache
     * @return bool True si succès, false sinon
     */
    public function delete(string $key): bool
    {
        $preparedKey = $this->prepareKey($key);
        $filePath = $this->getFilePath($preparedKey);

        if (file_exists($filePath)) {
            return @unlink($filePath);
        }

        return false;
    }

    /**
     * Vérifie si une clé existe dans le cache
     *
     * @param string $key Clé du cache
     * @return bool True si la clé existe
     */
    public function has(string $key): bool
    {
        $preparedKey = $this->prepareKey($key);
        $filePath = $this->getFilePath($preparedKey);

        if (!file_exists($filePath)) {
            return false;
        }

        try {
            $content = file_get_contents($filePath);
            if ($content === false) {
                return false;
            }

            $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

            // Vérifier l'expiration
            if (isset($data['expires']) && $data['expires'] !== null && $data['expires'] < time()) {
                $this->delete($key);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Vide tout le cache
     *
     * @return bool True si succès
     */
    public function clear(): bool
    {
        return $this->deleteDirectory($this->cachePath);
    }

    /**
     * Supprime récursivement un répertoire
     *
     * @param string $dir Répertoire à supprimer
     * @return bool True si succès
     */
    private function deleteDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return true;
        }

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                @unlink($path);
            }
        }

        return @rmdir($dir);
    }

    /**
     * Nettoie les fichiers de cache expirés
     *
     * @return int Nombre de fichiers supprimés
     */
    public function cleanExpired(): int
    {
        return $this->cleanExpiredRecursive($this->cachePath);
    }

    /**
     * Nettoie récursivement les fichiers expirés
     *
     * @param string $dir Répertoire à nettoyer
     * @return int Nombre de fichiers supprimés
     */
    private function cleanExpiredRecursive(string $dir): int
    {
        $count = 0;

        if (!is_dir($dir)) {
            return $count;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        $now = time();

        foreach ($files as $file) {
            $path = $dir . '/' . $file;

            if (is_dir($path)) {
                $count += $this->cleanExpiredRecursive($path);
            } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'cache') {
                try {
                    $content = @file_get_contents($path);
                    if ($content !== false) {
                        $data = json_decode($content, true);
                        if (isset($data['expires']) && $data['expires'] !== null && $data['expires'] < $now) {
                            if (@unlink($path)) {
                                $count++;
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    // Ignorer les erreurs
                }
            }
        }

        return $count;
    }
}

