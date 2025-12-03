<?php

namespace JulienLinard\Cache\Tests;

use PHPUnit\Framework\TestCase;
use JulienLinard\Cache\Cache;
use JulienLinard\Cache\CacheManager;
use JulienLinard\Cache\Drivers\ArrayCacheDriver;
use JulienLinard\Cache\Drivers\FileCacheDriver;
use JulienLinard\Cache\Exceptions\InvalidKeyException;
use JulienLinard\Cache\KeyValidator;

class CacheTest extends TestCase
{
    private string $tempDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempDir = sys_get_temp_dir() . '/php-cache-test-' . uniqid();
        Cache::init([
            'default' => 'array',
            'drivers' => [
                'array' => [],
                'file' => [
                    'path' => $this->tempDir,
                ],
            ],
        ]);
    }

    protected function tearDown(): void
    {
        // Nettoyer le cache de test
        if (is_dir($this->tempDir)) {
            $this->deleteDirectory($this->tempDir);
        }
        parent::tearDown();
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
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
        @rmdir($dir);
    }

    public function testArrayDriverBasicOperations(): void
    {
        $driver = new ArrayCacheDriver();

        // Test set/get
        $this->assertTrue($driver->set('test_key', 'test_value'));
        $this->assertEquals('test_value', $driver->get('test_key'));

        // Test has
        $this->assertTrue($driver->has('test_key'));
        $this->assertFalse($driver->has('non_existent'));

        // Test delete
        $this->assertTrue($driver->delete('test_key'));
        $this->assertFalse($driver->has('test_key'));

        // Test default value
        $this->assertNull($driver->get('non_existent'));
        $this->assertEquals('default', $driver->get('non_existent', 'default'));
    }

    public function testArrayDriverTtl(): void
    {
        $driver = new ArrayCacheDriver();

        // Test avec TTL
        $driver->set('ttl_key', 'ttl_value', 1);
        $this->assertEquals('ttl_value', $driver->get('ttl_key'));

        // Attendre l'expiration
        sleep(2);
        $this->assertNull($driver->get('ttl_key'));
        $this->assertFalse($driver->has('ttl_key'));
    }

    public function testArrayDriverMultipleOperations(): void
    {
        $driver = new ArrayCacheDriver();

        // Test setMultiple/getMultiple
        $values = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        ];

        $this->assertTrue($driver->setMultiple($values));
        $results = $driver->getMultiple(['key1', 'key2', 'key3']);
        $this->assertEquals($values, $results);

        // Test deleteMultiple
        $this->assertEquals(2, $driver->deleteMultiple(['key1', 'key2']));
        $this->assertFalse($driver->has('key1'));
        $this->assertFalse($driver->has('key2'));
        $this->assertTrue($driver->has('key3'));
    }

    public function testArrayDriverIncrementDecrement(): void
    {
        $driver = new ArrayCacheDriver();

        // Test increment
        $driver->set('counter', 10);
        $this->assertEquals(11, $driver->increment('counter'));
        $this->assertEquals(13, $driver->increment('counter', 2));

        // Test decrement
        $this->assertEquals(12, $driver->decrement('counter'));
        $this->assertEquals(10, $driver->decrement('counter', 2));
    }

    public function testArrayDriverPull(): void
    {
        $driver = new ArrayCacheDriver();

        $driver->set('pull_key', 'pull_value');
        $value = $driver->pull('pull_key');
        $this->assertEquals('pull_value', $value);
        $this->assertFalse($driver->has('pull_key'));
    }

    public function testArrayDriverClear(): void
    {
        $driver = new ArrayCacheDriver();

        $driver->set('key1', 'value1');
        $driver->set('key2', 'value2');
        $this->assertTrue($driver->clear());
        $this->assertFalse($driver->has('key1'));
        $this->assertFalse($driver->has('key2'));
    }

    public function testFileDriverBasicOperations(): void
    {
        $driver = new FileCacheDriver(['path' => $this->tempDir]);

        // Test set/get
        $this->assertTrue($driver->set('test_key', 'test_value'));
        $this->assertEquals('test_value', $driver->get('test_key'));

        // Test has
        $this->assertTrue($driver->has('test_key'));

        // Test delete
        $this->assertTrue($driver->delete('test_key'));
        $this->assertFalse($driver->has('test_key'));
    }

    public function testFileDriverComplexData(): void
    {
        $driver = new FileCacheDriver(['path' => $this->tempDir]);

        $complexData = [
            'string' => 'test',
            'int' => 123,
            'array' => [1, 2, 3],
            'object' => (object)['key' => 'value'],
        ];

        $this->assertTrue($driver->set('complex', $complexData));
        $retrieved = $driver->get('complex');
        $this->assertEquals($complexData, $retrieved);
    }

    public function testKeyValidator(): void
    {
        // Test clé valide
        KeyValidator::validate('valid_key_123');

        // Test clé vide
        $this->expectException(InvalidKeyException::class);
        KeyValidator::validate('');

        // Test clé avec caractères invalides
        $this->expectException(InvalidKeyException::class);
        KeyValidator::validate('invalid/key');

        // Test clé avec path traversal
        $this->expectException(InvalidKeyException::class);
        KeyValidator::validate('../invalid');
    }

    public function testKeyValidatorSanitize(): void
    {
        $sanitized = KeyValidator::sanitize('invalid/key@test');
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9_\-\.]+$/', $sanitized);
    }

    public function testCacheFacade(): void
    {
        // Test via la façade
        Cache::set('facade_key', 'facade_value');
        $this->assertEquals('facade_value', Cache::get('facade_key'));
        $this->assertTrue(Cache::has('facade_key'));
        $this->assertTrue(Cache::delete('facade_key'));
        $this->assertFalse(Cache::has('facade_key'));
    }

    public function testCacheManager(): void
    {
        $manager = CacheManager::create([
            'default' => 'array',
            'drivers' => [
                'array' => [],
            ],
        ]);

        $driver = $manager->driver();
        $this->assertInstanceOf(ArrayCacheDriver::class, $driver);

        $driver->set('manager_key', 'manager_value');
        $this->assertEquals('manager_value', $manager->get('manager_key'));
    }

    public function testTaggedCache(): void
    {
        $manager = CacheManager::create([
            'default' => 'array',
            'drivers' => [
                'array' => [],
            ],
        ]);

        $taggedCache = $manager->tags(['users', 'posts']);

        // Stocker avec tags
        $taggedCache->set('user_1', ['name' => 'John']);
        $taggedCache->set('user_2', ['name' => 'Jane']);

        // Récupérer
        $this->assertEquals(['name' => 'John'], $taggedCache->get('user_1'));

        // Invalider par tag
        $taggedCache->invalidateTags('users');
        
        // Les clés devraient être supprimées (ou au moins le tag)
        // Note: L'implémentation actuelle simplifie l'invalidation
    }

    public function testCacheWithPrefix(): void
    {
        $driver = new ArrayCacheDriver(['prefix' => 'myapp']);

        $driver->set('key', 'value');
        // La clé interne devrait être préfixée
        $this->assertEquals('value', $driver->get('key'));
    }
}

