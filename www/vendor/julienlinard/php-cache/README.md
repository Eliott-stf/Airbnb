# PHP Cache

[🇫🇷 Read in French](README.fr.md) | [🇬🇧 Read in English](README.md)

## 💝 Support the project

If this package is useful to you, consider [becoming a sponsor](https://github.com/sponsors/julien-lin) to support the development and maintenance of this open source project.

---

A modern and secure caching system for PHP 8+ with support for multiple drivers (File, Redis, Memcached, Array), tags, TTL, and invalidation.

## 🚀 Installation

```bash
composer require julienlinard/php-cache
```

**Requirements**: PHP 8.0 or higher

## ⚡ Quick Start

### Basic Configuration

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use JulienLinard\Cache\Cache;

// Initialize with configuration
Cache::init([
    'default' => 'file', // or 'array', 'redis'
    'drivers' => [
        'array' => [],
        'file' => [
            'path' => __DIR__ . '/cache',
            'ttl' => 3600, // Default TTL in seconds
        ],
        'redis' => [
            'host' => '127.0.0.1',
            'port' => 6379,
            'password' => null,
            'database' => 0,
        ],
    ],
]);

// Simple usage
Cache::set('user_123', ['name' => 'John', 'email' => 'john@example.com'], 3600);
$user = Cache::get('user_123');
```

## 📋 Features

- ✅ **Multiple Drivers**: Array, File, Redis
- ✅ **TTL (Time To Live)**: Automatic entry expiration
- ✅ **Tags**: Tag system for grouped invalidation
- ✅ **Security**: Key validation, protection against injections
- ✅ **Secure Serialization**: JSON usage with validation
- ✅ **Multiple Operations**: getMultiple, setMultiple, deleteMultiple
- ✅ **Increment/Decrement**: Support for numeric values
- ✅ **Fluid Interface**: Simple and intuitive API

## 📖 Documentation

### Available Drivers

#### Array Driver (Memory)

The Array driver stores data in memory. Useful for testing and development.

```php
use JulienLinard\Cache\Cache;

Cache::init([
    'default' => 'array',
    'drivers' => [
        'array' => [
            'prefix' => 'myapp', // Optional prefix for all keys
            'ttl' => 3600, // Default TTL
        ],
    ],
]);
```

#### File Driver (Disk)

The File driver stores data in files on the filesystem.

```php
Cache::init([
    'default' => 'file',
    'drivers' => [
        'file' => [
            'path' => __DIR__ . '/cache', // Cache directory
            'prefix' => 'myapp',
            'ttl' => 3600,
            'file_permissions' => 0644, // File permissions
            'directory_permissions' => 0755, // Directory permissions
        ],
    ],
]);
```

#### Redis Driver

The Redis driver requires the PHP Redis extension.

```bash
# Install Redis extension
pecl install redis
```

```php
Cache::init([
    'default' => 'redis',
    'drivers' => [
        'redis' => [
            'host' => '127.0.0.1',
            'port' => 6379,
            'password' => 'your_password', // Optional
            'database' => 0,
            'timeout' => 2.0,
            'persistent' => false, // Persistent connection
            'persistent_id' => null,
            'prefix' => 'myapp',
            'ttl' => 3600,
        ],
    ],
]);
```

### Basic Operations

#### Store a Value

```php
// With default TTL
Cache::set('key', 'value');

// With custom TTL (in seconds)
Cache::set('key', 'value', 3600);

// Complex data
Cache::set('user', [
    'id' => 123,
    'name' => 'John',
    'email' => 'john@example.com',
], 3600);
```

#### Retrieve a Value

```php
// Simple retrieval
$value = Cache::get('key');

// With default value
$value = Cache::get('key', 'default_value');

// Complex data
$user = Cache::get('user', []);
```

#### Check Existence

```php
if (Cache::has('key')) {
    // Key exists
}
```

#### Delete a Value

```php
Cache::delete('key');
```

#### Clear All Cache

```php
Cache::clear();
```

### Multiple Operations

#### Retrieve Multiple Values

```php
$values = Cache::getMultiple(['key1', 'key2', 'key3'], null);
// Returns: ['key1' => value1, 'key2' => value2, 'key3' => value3]
```

#### Store Multiple Values

```php
Cache::setMultiple([
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => 'value3',
], 3600); // Common TTL for all keys
```

#### Delete Multiple Keys

```php
$deleted = Cache::deleteMultiple(['key1', 'key2', 'key3']);
// Returns the number of deleted keys
```

### Increment and Decrement

```php
// Increment
Cache::set('counter', 0);
Cache::increment('counter'); // 1
Cache::increment('counter', 5); // 6

// Decrement
Cache::decrement('counter'); // 5
Cache::decrement('counter', 2); // 3
```

### Pull (Retrieve and Delete)

```php
$value = Cache::pull('key'); // Retrieves and deletes in one operation
```

### Using a Specific Driver

```php
// Use a specific driver
Cache::set('key', 'value', 3600, 'redis');
$value = Cache::get('key', null, 'redis');

// Or get the driver directly
$redisCache = Cache::driver('redis');
$redisCache->set('key', 'value');
```

### Tag System

Tags allow grouping cache entries and invalidating them together.

```php
// Create a tagged cache
$taggedCache = Cache::tags(['users', 'posts']);

// Store values with tags
$taggedCache->set('user_1', ['name' => 'John']);
$taggedCache->set('user_2', ['name' => 'Jane']);

// Get keys associated with a tag
$keys = $taggedCache->getKeysByTag('users');

// Invalidate all keys with a tag
$taggedCache->invalidateTags('users');
// or multiple tags
$taggedCache->invalidateTags(['users', 'posts']);
```

### Advanced Usage with CacheManager

```php
use JulienLinard\Cache\CacheManager;

$manager = CacheManager::getInstance([
    'default' => 'file',
    'drivers' => [
        'file' => ['path' => __DIR__ . '/cache'],
    ],
]);

// Get a driver
$driver = $manager->driver('file');

// Register a custom driver
$customDriver = new MyCustomDriver();
$manager->registerDriver('custom', $customDriver);

// Change the default driver
$manager->setDefaultDriver('redis');
```

### Key Validation

The system automatically validates keys for security:

- ✅ Allowed characters: letters, numbers, `_`, `-`, `.`
- ✅ Maximum length: 250 characters
- ✅ Protection against path injections (`..`, `/`, `\`)

```php
use JulienLinard\Cache\KeyValidator;

// Validate a key
try {
    KeyValidator::validate('valid_key_123');
} catch (InvalidKeyException $e) {
    // Invalid key
}

// Sanitize a key
$cleanKey = KeyValidator::sanitize('invalid/key@test');
// Returns: 'invalid_key_test'
```

### Error Handling

```php
use JulienLinard\Cache\Exceptions\CacheException;
use JulienLinard\Cache\Exceptions\InvalidKeyException;
use JulienLinard\Cache\Exceptions\DriverException;

try {
    Cache::set('key', 'value');
} catch (InvalidKeyException $e) {
    // Invalid key
} catch (DriverException $e) {
    // Driver error
} catch (CacheException $e) {
    // Other cache error
}
```

## 🔒 Security

### Implemented Security Measures

1. **Key Validation**: Protection against path injections
2. **Secure Serialization**: JSON usage with strict validation
3. **File Permissions**: Permission control for File driver
4. **Atomic Writing**: File driver uses temporary files to prevent corruption
5. **Input Validation**: All entries are validated before storage

### Best Practices

```php
// ✅ GOOD: Simple and descriptive keys
Cache::set('user_123', $userData);

// ❌ BAD: Keys with special characters
Cache::set('user/123', $userData); // Throws exception

// ✅ GOOD: Use prefixes
Cache::init([
    'drivers' => [
        'file' => ['prefix' => 'myapp'],
    ],
]);

// ✅ GOOD: Validate data before caching
$data = validateAndSanitize($userInput);
Cache::set('key', $data);
```

## 🧪 Tests

```bash
# Run tests
composer test

# With code coverage
composer test-coverage
```

## 📝 Usage Examples

### Database Query Caching

```php
use JulienLinard\Cache\Cache;

function getUser(int $id): array
{
    $cacheKey = "user_{$id}";
    
    // Check cache
    if (Cache::has($cacheKey)) {
        return Cache::get($cacheKey);
    }
    
    // Retrieve from database
    $user = fetchUserFromDatabase($id);
    
    // Cache for 1 hour
    Cache::set($cacheKey, $user, 3600);
    
    return $user;
}
```

### Cache with Tag Invalidation

```php
use JulienLinard\Cache\Cache;

// Store users with tag
$usersCache = Cache::tags('users');
$usersCache->set('user_1', $user1, 3600);
$usersCache->set('user_2', $user2, 3600);

// When a user is updated, invalidate the tag
function updateUser(int $id, array $data): void
{
    // Update in database
    updateUserInDatabase($id, $data);
    
    // Invalidate all entries with 'users' tag
    $usersCache = Cache::tags('users');
    $usersCache->invalidateTags('users');
}
```

### View/Template Caching

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

### Counter with Expiration

```php
use JulienLinard\Cache\Cache;

function incrementPageViews(string $pageId): int
{
    $key = "page_views_{$pageId}";
    
    if (!Cache::has($key)) {
        // Initialize with 24h expiration
        Cache::set($key, 0, 86400);
    }
    
    return Cache::increment($key);
}
```

## 🤝 Integration with Other Packages

### With doctrine-php

```php
use JulienLinard\Cache\Cache;
use JulienLinard\Doctrine\EntityManager;

function getCachedEntity(EntityManager $em, string $entityClass, int $id): ?object
{
    $cacheKey = strtolower($entityClass) . "_{$id}";
    
    if (Cache::has($cacheKey)) {
        $data = Cache::get($cacheKey);
        // Rebuild entity from data
        return $em->getRepository($entityClass)->find($id);
    }
    
    $entity = $em->getRepository($entityClass)->find($id);
    
    if ($entity) {
        // Store entity data
        Cache::set($cacheKey, $entity->toArray(), 3600);
    }
    
    return $entity;
}
```

## 📚 API Reference

### Cache (Facade)

- `Cache::init(array $config)` : Initialize the manager
- `Cache::get(string $key, mixed $default = null, ?string $driver = null)` : Retrieve a value
- `Cache::set(string $key, mixed $value, ?int $ttl = null, ?string $driver = null)` : Store a value
- `Cache::has(string $key, ?string $driver = null)` : Check existence
- `Cache::delete(string $key, ?string $driver = null)` : Delete a value
- `Cache::clear(?string $driver = null)` : Clear cache
- `Cache::increment(string $key, int $value = 1, ?string $driver = null)` : Increment
- `Cache::decrement(string $key, int $value = 1, ?string $driver = null)` : Decrement
- `Cache::pull(string $key, mixed $default = null, ?string $driver = null)` : Retrieve and delete
- `Cache::tags(string|array $tags, ?string $driver = null)` : Create tagged cache
- `Cache::driver(?string $driver = null)` : Get a driver

### CacheInterface

All drivers implement `CacheInterface` with the following methods:

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

## 🐛 Troubleshooting

### File Driver Not Working

Check that the cache directory exists and is writable:

```php
$cachePath = __DIR__ . '/cache';
if (!is_dir($cachePath)) {
    mkdir($cachePath, 0755, true);
}
```

### Redis Driver Not Connecting

1. Check that Redis extension is installed: `php -m | grep redis`
2. Check that Redis is running: `redis-cli ping`
3. Check connection parameters in configuration

### "Invalid Key" Error

Keys must follow this format:
- Allowed characters: `a-z`, `A-Z`, `0-9`, `_`, `-`, `.`
- Maximum length: 250 characters
- No relative paths (`..`, `/`, `\`)

## 📝 License

MIT License - See the LICENSE file for more details.

## 🤝 Contributing

Contributions are welcome! Feel free to open an issue or a pull request.

## 📧 Support

For any questions or issues, please open an issue on GitHub.

## 💝 Support the project

If this package is useful to you, consider [becoming a sponsor](https://github.com/sponsors/julien-lin) to support the development and maintenance of this open source project.

---

**Developed with ❤️ by Julien Linard**
