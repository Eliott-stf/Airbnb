<?php

/**
 * Exemple d'utilisation de PHP Cache
 */

require_once __DIR__ . '/vendor/autoload.php';

use JulienLinard\Cache\Cache;
use JulienLinard\Cache\Exceptions\CacheException;

// ============================================
// CONFIGURATION
// ============================================

Cache::init([
    'default' => 'file',
    'drivers' => [
        'array' => [
            'prefix' => 'example',
        ],
        'file' => [
            'path' => __DIR__ . '/cache',
            'prefix' => 'example',
            'ttl' => 3600, // 1 heure par défaut
        ],
        'redis' => [
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ],
    ],
]);

// ============================================
// EXEMPLE 1 : Opérations de base
// ============================================

echo "=== Exemple 1 : Opérations de base ===\n";

// Stocker une valeur simple
Cache::set('name', 'John Doe', 3600);
echo "Valeur stockée : name = 'John Doe'\n";

// Récupérer une valeur
$name = Cache::get('name');
echo "Valeur récupérée : name = '{$name}'\n";

// Vérifier l'existence
if (Cache::has('name')) {
    echo "La clé 'name' existe\n";
}

// Supprimer une valeur
Cache::delete('name');
echo "Clé 'name' supprimée\n\n";

// ============================================
// EXEMPLE 2 : Données complexes
// ============================================

echo "=== Exemple 2 : Données complexes ===\n";

$user = [
    'id' => 123,
    'name' => 'Jane Smith',
    'email' => 'jane@example.com',
    'roles' => ['admin', 'user'],
];

Cache::set('user_123', $user, 3600);
$cachedUser = Cache::get('user_123');

echo "Utilisateur mis en cache :\n";
print_r($cachedUser);
echo "\n";

// ============================================
// EXEMPLE 3 : Opérations multiples
// ============================================

echo "=== Exemple 3 : Opérations multiples ===\n";

// Stocker plusieurs valeurs
Cache::setMultiple([
    'product_1' => ['name' => 'Laptop', 'price' => 999.99],
    'product_2' => ['name' => 'Mouse', 'price' => 29.99],
    'product_3' => ['name' => 'Keyboard', 'price' => 79.99],
], 3600);

// Récupérer plusieurs valeurs
$products = Cache::getMultiple(['product_1', 'product_2', 'product_3']);
echo "Produits récupérés :\n";
foreach ($products as $key => $product) {
    echo "  {$key} : {$product['name']} - \${$product['price']}\n";
}
echo "\n";

// ============================================
// EXEMPLE 4 : Incrémentation/Décrémentation
// ============================================

echo "=== Exemple 4 : Compteurs ===\n";

// Initialiser un compteur
Cache::set('page_views', 0, 86400); // 24 heures

// Incrémenter
Cache::increment('page_views');
Cache::increment('page_views', 5);

$views = Cache::get('page_views');
echo "Nombre de vues : {$views}\n";

// Décrémenter
Cache::decrement('page_views', 2);
$views = Cache::get('page_views');
echo "Nombre de vues après décrémentation : {$views}\n\n";

// ============================================
// EXEMPLE 5 : Pull (récupérer et supprimer)
// ============================================

echo "=== Exemple 5 : Pull ===\n";

Cache::set('temp_data', 'Données temporaires', 3600);
$data = Cache::pull('temp_data');
echo "Données récupérées et supprimées : {$data}\n";
echo "La clé existe encore ? " . (Cache::has('temp_data') ? 'Oui' : 'Non') . "\n\n";

// ============================================
// EXEMPLE 6 : Tags
// ============================================

echo "=== Exemple 6 : Cache avec tags ===\n";

// Créer un cache avec tags
$usersCache = Cache::tags(['users', 'profiles']);

// Stocker des utilisateurs avec tags
$usersCache->set('user_1', ['name' => 'Alice', 'role' => 'admin'], 3600);
$usersCache->set('user_2', ['name' => 'Bob', 'role' => 'user'], 3600);

echo "Utilisateurs stockés avec tags 'users' et 'profiles'\n";

// Récupérer les clés associées à un tag
$keys = $usersCache->getKeysByTag('users');
echo "Clés avec le tag 'users' : " . implode(', ', $keys) . "\n";

// Invalider toutes les entrées avec un tag
$usersCache->invalidateTags('users');
echo "Tag 'users' invalidé\n\n";

// ============================================
// EXEMPLE 7 : Utilisation avec driver spécifique
// ============================================

echo "=== Exemple 7 : Driver spécifique ===\n";

// Utiliser le driver Array directement
$arrayCache = Cache::driver('array');
$arrayCache->set('array_key', 'Valeur en mémoire', 3600);
echo "Valeur stockée dans le driver Array\n";

// Utiliser le driver File
try {
    $fileCache = Cache::driver('file');
    $fileCache->set('file_key', 'Valeur sur disque', 3600);
    echo "Valeur stockée dans le driver File\n";
} catch (CacheException $e) {
    echo "Erreur avec le driver File : {$e->getMessage()}\n";
}

echo "\n";

// ============================================
// EXEMPLE 8 : Cache de requêtes (pattern classique)
// ============================================

echo "=== Exemple 8 : Cache de requêtes ===\n";

function getCachedUser(int $userId): array
{
    $cacheKey = "user_{$userId}";
    
    // Vérifier le cache
    if (Cache::has($cacheKey)) {
        echo "  Récupération depuis le cache\n";
        return Cache::get($cacheKey);
    }
    
    // Simuler une requête à la base de données
    echo "  Requête à la base de données\n";
    $user = [
        'id' => $userId,
        'name' => "User {$userId}",
        'email' => "user{$userId}@example.com",
    ];
    
    // Mettre en cache pour 1 heure
    Cache::set($cacheKey, $user, 3600);
    
    return $user;
}

// Premier appel : va à la base de données
$user1 = getCachedUser(1);
echo "  Utilisateur 1 : {$user1['name']}\n";

// Deuxième appel : utilise le cache
$user1Cached = getCachedUser(1);
echo "  Utilisateur 1 (cached) : {$user1Cached['name']}\n\n";

// ============================================
// EXEMPLE 9 : Gestion des erreurs
// ============================================

echo "=== Exemple 9 : Gestion des erreurs ===\n";

try {
    // Tentative avec une clé invalide
    Cache::set('invalid/key', 'value');
} catch (\JulienLinard\Cache\Exceptions\InvalidKeyException $e) {
    echo "Erreur capturée : {$e->getMessage()}\n";
}

echo "\n";

// ============================================
// NETTOYAGE
// ============================================

echo "=== Nettoyage ===\n";
Cache::clear('file');
echo "Cache File vidé\n";

Cache::clear('array');
echo "Cache Array vidé\n";

echo "\nExemples terminés !\n";

