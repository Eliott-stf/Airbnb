# PHP Cache

[🇬🇧 Lire en anglais](README.md) | [🇫🇷 Lire en français](README.fr.md)

## 💝 Soutenir le projet

Si ce package vous est utile, envisagez de [devenir un sponsor](https://github.com/sponsors/julien-lin) pour soutenir le développement et la maintenance de ce projet open source.

---

Un système de cache moderne et sécurisé pour PHP 8+ avec support de multiples drivers (File, Redis, Memcached, Array), tags, TTL et invalidation.

## 🚀 Installation

```bash
composer require julienlinard/php-cache
```

**Requirements** : PHP 8.0 ou supérieur

## ⚡ Démarrage rapide

### Configuration de base

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use JulienLinard\Cache\Cache;

// Initialisation avec configuration
Cache::init([
    'default' => 'file', // ou 'array', 'redis'
    'drivers' => [
        'array' => [],
        'file' => [
            'path' => __DIR__ . '/cache',
            'ttl' => 3600, // TTL par défaut en secondes
        ],
        'redis' => [
            'host' => '127.0.0.1',
            'port' => 6379,
            'password' => null,
            'database' => 0,
        ],
    ],
]);

// Utilisation simple
Cache::set('user_123', ['name' => 'John', 'email' => 'john@example.com'], 3600);
$user = Cache::get('user_123');
```

## 📋 Fonctionnalités

- ✅ **Multiples drivers** : Array, File, Redis
- ✅ **TTL (Time To Live)** : Expiration automatique des entrées
- ✅ **Tags** : Système de tags pour invalidation groupée
- ✅ **Sécurité** : Validation des clés, protection contre les injections
- ✅ **Sérialisation sécurisée** : Utilisation de JSON avec validation
- ✅ **Opérations multiples** : getMultiple, setMultiple, deleteMultiple
- ✅ **Incrémentation/Décrémentation** : Support des valeurs numériques
- ✅ **Interface fluide** : API simple et intuitive

## 📖 Documentation

### Drivers disponibles

#### Array Driver (Mémoire)

Le driver Array stocke les données en mémoire. Utile pour les tests et le développement.

```php
use JulienLinard\Cache\Cache;

Cache::init([
    'default' => 'array',
    'drivers' => [
        'array' => [
            'prefix' => 'myapp', // Préfixe optionnel pour toutes les clés
            'ttl' => 3600, // TTL par défaut
        ],
    ],
]);
```

#### File Driver (Disque)

Le driver File stocke les données dans des fichiers sur le système de fichiers.

```php
Cache::init([
    'default' => 'file',
    'drivers' => [
        'file' => [
            'path' => __DIR__ . '/cache', // Répertoire de cache
            'prefix' => 'myapp',
            'ttl' => 3600,
            'file_permissions' => 0644, // Permissions des fichiers
            'directory_permissions' => 0755, // Permissions des répertoires
        ],
    ],
]);
```

#### Redis Driver

Le driver Redis nécessite l'extension PHP Redis.

```bash
# Installation de l'extension Redis
pecl install redis
```

```php
Cache::init([
    'default' => 'redis',
    'drivers' => [
        'redis' => [
            'host' => '127.0.0.1',
            'port' => 6379,
            'password' => 'your_password', // Optionnel
            'database' => 0,
            'timeout' => 2.0,
            'persistent' => false, // Connexion persistante
            'persistent_id' => null,
            'prefix' => 'myapp',
            'ttl' => 3600,
        ],
    ],
]);
```

### Opérations de base

#### Stocker une valeur

```php
// Avec TTL par défaut
Cache::set('key', 'value');

// Avec TTL personnalisé (en secondes)
Cache::set('key', 'value', 3600);

// Données complexes
Cache::set('user', [
    'id' => 123,
    'name' => 'John',
    'email' => 'john@example.com',
], 3600);
```

#### Récupérer une valeur

```php
// Récupération simple
$value = Cache::get('key');

// Avec valeur par défaut
$value = Cache::get('key', 'default_value');

// Données complexes
$user = Cache::get('user', []);
```

#### Vérifier l'existence

```php
if (Cache::has('key')) {
    // La clé existe
}
```

#### Supprimer une valeur

```php
Cache::delete('key');
```

#### Vider tout le cache

```php
Cache::clear();
```

### Opérations multiples

#### Récupérer plusieurs valeurs

```php
$values = Cache::getMultiple(['key1', 'key2', 'key3'], null);
// Retourne: ['key1' => value1, 'key2' => value2, 'key3' => value3]
```

#### Stocker plusieurs valeurs

```php
Cache::setMultiple([
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => 'value3',
], 3600); // TTL commun pour toutes les clés
```

#### Supprimer plusieurs clés

```php
$deleted = Cache::deleteMultiple(['key1', 'key2', 'key3']);
// Retourne le nombre de clés supprimées
```

### Incrémentation et décrémentation

```php
// Incrémenter
Cache::set('counter', 0);
Cache::increment('counter'); // 1
Cache::increment('counter', 5); // 6

// Décrémenter
Cache::decrement('counter'); // 5
Cache::decrement('counter', 2); // 3
```

### Pull (récupérer et supprimer)

```php
$value = Cache::pull('key'); // Récupère et supprime en une opération
```

### Utilisation avec un driver spécifique

```php
// Utiliser un driver spécifique
Cache::set('key', 'value', 3600, 'redis');
$value = Cache::get('key', null, 'redis');

// Ou récupérer le driver directement
$redisCache = Cache::driver('redis');
$redisCache->set('key', 'value');
```

### Système de tags

Les tags permettent de grouper des entrées de cache et de les invalider ensemble.

```php
// Créer un cache avec tags
$taggedCache = Cache::tags(['users', 'posts']);

// Stocker des valeurs avec tags
$taggedCache->set('user_1', ['name' => 'John']);
$taggedCache->set('user_2', ['name' => 'Jane']);

// Récupérer les clés associées à un tag
$keys = $taggedCache->getKeysByTag('users');

// Invalider toutes les clés avec un tag
$taggedCache->invalidateTags('users');
// ou plusieurs tags
$taggedCache->invalidateTags(['users', 'posts']);
```

### Utilisation avancée avec CacheManager

```php
use JulienLinard\Cache\CacheManager;

$manager = CacheManager::getInstance([
    'default' => 'file',
    'drivers' => [
        'file' => ['path' => __DIR__ . '/cache'],
    ],
]);

// Récupérer un driver
$driver = $manager->driver('file');

// Enregistrer un driver personnalisé
$customDriver = new MyCustomDriver();
$manager->registerDriver('custom', $customDriver);

// Changer le driver par défaut
$manager->setDefaultDriver('redis');
```

### Validation des clés

Le système valide automatiquement les clés pour la sécurité :

- ✅ Caractères autorisés : lettres, chiffres, `_`, `-`, `.`
- ✅ Longueur maximale : 250 caractères
- ✅ Protection contre les injections de chemins (`..`, `/`, `\`)

```php
use JulienLinard\Cache\KeyValidator;

// Valider une clé
try {
    KeyValidator::validate('valid_key_123');
} catch (InvalidKeyException $e) {
    // Clé invalide
}

// Nettoyer une clé
$cleanKey = KeyValidator::sanitize('invalid/key@test');
// Retourne: 'invalid_key_test'
```

### Gestion des erreurs

```php
use JulienLinard\Cache\Exceptions\CacheException;
use JulienLinard\Cache\Exceptions\InvalidKeyException;
use JulienLinard\Cache\Exceptions\DriverException;

try {
    Cache::set('key', 'value');
} catch (InvalidKeyException $e) {
    // Clé invalide
} catch (DriverException $e) {
    // Erreur avec le driver
} catch (CacheException $e) {
    // Autre erreur de cache
}
```

## 🔒 Sécurité

### Mesures de sécurité implémentées

1. **Validation des clés** : Protection contre les injections de chemins
2. **Sérialisation sécurisée** : Utilisation de JSON avec validation stricte
3. **Permissions de fichiers** : Contrôle des permissions pour le driver File
4. **Écriture atomique** : Le driver File utilise des fichiers temporaires pour éviter la corruption
5. **Validation des entrées** : Toutes les entrées sont validées avant stockage

### Bonnes pratiques

```php
// ✅ BON : Clés simples et descriptives
Cache::set('user_123', $userData);

// ❌ MAUVAIS : Clés avec caractères spéciaux
Cache::set('user/123', $userData); // Lève une exception

// ✅ BON : Utiliser des préfixes
Cache::init([
    'drivers' => [
        'file' => ['prefix' => 'myapp'],
    ],
]);

// ✅ BON : Valider les données avant de les mettre en cache
$data = validateAndSanitize($userInput);
Cache::set('key', $data);
```

## 🧪 Tests

```bash
# Exécuter les tests
composer test

# Avec couverture de code
composer test-coverage
```

## 📝 Exemples d'utilisation

### Cache de requêtes de base de données

```php
use JulienLinard\Cache\Cache;

function getUser(int $id): array
{
    $cacheKey = "user_{$id}";
    
    // Vérifier le cache
    if (Cache::has($cacheKey)) {
        return Cache::get($cacheKey);
    }
    
    // Récupérer depuis la base de données
    $user = fetchUserFromDatabase($id);
    
    // Mettre en cache pour 1 heure
    Cache::set($cacheKey, $user, 3600);
    
    return $user;
}
```

### Cache avec invalidation par tags

```php
use JulienLinard\Cache\Cache;

// Stocker des utilisateurs avec tag
$usersCache = Cache::tags('users');
$usersCache->set('user_1', $user1, 3600);
$usersCache->set('user_2', $user2, 3600);

// Quand un utilisateur est modifié, invalider le tag
function updateUser(int $id, array $data): void
{
    // Mettre à jour en base de données
    updateUserInDatabase($id, $data);
    
    // Invalider toutes les entrées avec le tag 'users'
    $usersCache = Cache::tags('users');
    $usersCache->invalidateTags('users');
}
```

### Cache de vues/templates

```php
use JulienLinard\Cache\Cache;

function renderView(string $template, array $data): string
{
    $cacheKey = 'view_' . md5($template . serialize($data));
    
    if (Cache::has($cacheKey)) {
        return Cache::get($cacheKey);
    }
    
    $html = renderTemplate($template, $data);
    Cache::set($cacheKey, $html, 1800); // 30 minutes
    
    return $html;
}
```

### Compteur avec expiration

```php
use JulienLinard\Cache\Cache;

function incrementPageViews(string $pageId): int
{
    $key = "page_views_{$pageId}";
    
    if (!Cache::has($key)) {
        // Initialiser avec expiration de 24h
        Cache::set($key, 0, 86400);
    }
    
    return Cache::increment($key);
}
```

## 🤝 Intégration avec d'autres packages

### Avec doctrine-php

```php
use JulienLinard\Cache\Cache;
use JulienLinard\Doctrine\EntityManager;

function getCachedEntity(EntityManager $em, string $entityClass, int $id): ?object
{
    $cacheKey = strtolower($entityClass) . "_{$id}";
    
    if (Cache::has($cacheKey)) {
        $data = Cache::get($cacheKey);
        // Reconstruire l'entité depuis les données
        return $em->getRepository($entityClass)->find($id);
    }
    
    $entity = $em->getRepository($entityClass)->find($id);
    
    if ($entity) {
        // Stocker les données de l'entité
        Cache::set($cacheKey, $entity->toArray(), 3600);
    }
    
    return $entity;
}
```

## 📚 API Reference

### Cache (Facade)

- `Cache::init(array $config)` : Initialise le gestionnaire
- `Cache::get(string $key, mixed $default = null, ?string $driver = null)` : Récupère une valeur
- `Cache::set(string $key, mixed $value, ?int $ttl = null, ?string $driver = null)` : Stocke une valeur
- `Cache::has(string $key, ?string $driver = null)` : Vérifie l'existence
- `Cache::delete(string $key, ?string $driver = null)` : Supprime une valeur
- `Cache::clear(?string $driver = null)` : Vide le cache
- `Cache::increment(string $key, int $value = 1, ?string $driver = null)` : Incrémente
- `Cache::decrement(string $key, int $value = 1, ?string $driver = null)` : Décrémente
- `Cache::pull(string $key, mixed $default = null, ?string $driver = null)` : Récupère et supprime
- `Cache::tags(string|array $tags, ?string $driver = null)` : Crée un cache tagué
- `Cache::driver(?string $driver = null)` : Récupère un driver

### CacheInterface

Tous les drivers implémentent `CacheInterface` avec les méthodes suivantes :

- `get(string $key, mixed $default = null): mixed`
- `set(string $key, mixed $value, ?int $ttl = null): bool`
- `delete(string $key): bool`
- `has(string $key): bool`
- `clear(): bool`
- `getMultiple(array $keys, mixed $default = null): array`
- `setMultiple(array $values, ?int $ttl = null): bool`
- `deleteMultiple(array $keys): int`
- `increment(string $key, int $value = 1): int|false`
- `decrement(string $key, int $value = 1): int|false`
- `pull(string $key, mixed $default = null): mixed`

## 🐛 Dépannage

### Le driver File ne fonctionne pas

Vérifiez que le répertoire de cache existe et est accessible en écriture :

```php
$cachePath = __DIR__ . '/cache';
if (!is_dir($cachePath)) {
    mkdir($cachePath, 0755, true);
}
```

### Le driver Redis ne se connecte pas

1. Vérifiez que l'extension Redis est installée : `php -m | grep redis`
2. Vérifiez que Redis est démarré : `redis-cli ping`
3. Vérifiez les paramètres de connexion dans la configuration

### Erreur "Clé invalide"

Les clés doivent respecter le format suivant :
- Caractères autorisés : `a-z`, `A-Z`, `0-9`, `_`, `-`, `.`
- Longueur maximale : 250 caractères
- Pas de chemins relatifs (`..`, `/`, `\`)

## 📄 Licence

MIT License - Voir le fichier LICENSE pour plus de détails.

## 🤝 Contribuer

Les contributions sont les bienvenues ! N'hésitez pas à ouvrir une issue ou une pull request.

## 📧 Support

Pour toute question ou problème, veuillez ouvrir une issue sur GitHub.

## 💝 Soutenir le projet

Si ce package vous est utile, envisagez de [devenir un sponsor](https://github.com/sponsors/julien-lin) pour soutenir le développement et la maintenance de ce projet open source.

---

**Développé avec ❤️ par Julien Linard**

