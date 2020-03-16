<?php declare(strict_types=1);

namespace J6s\ApcuFallbackCache;

use Neos\Cache\Backend\AbstractBackend;
use Neos\Cache\Backend\ApcuBackend;
use Neos\Cache\Backend\BackendInterface;
use Neos\Cache\Backend\IterableBackendInterface;
use Neos\Cache\Backend\PhpCapableBackendInterface;
use Neos\Cache\Backend\TaggableBackendInterface;
use Neos\Cache\EnvironmentConfiguration;
use Neos\Cache\Frontend\FrontendInterface;

class Backend implements
    BackendInterface,
    TaggableBackendInterface,
    IterableBackendInterface,
    PhpCapableBackendInterface
{

    /**
     * @var AbstractBackend|TaggableBackendInterface|IterableBackendInterface|PhpCapableBackendInterface
     */
    protected $backend;

    /**
     * @param mixed[] $options
     */
    public function __construct(EnvironmentConfiguration $environmentConfiguration = null, array $options = [])
    {
        $fallback = $options['fallback'];
        unset($options['fallback']);

        if (\extension_loaded('apcu')) {
            $this->backend = new ApcuBackend($environmentConfiguration, $options);
        } else {
            $class = $options['backend'];
            $this->backend = new $class($environmentConfiguration, $fallback['backendOptions'] ?? []);
        }
    }

    public function set(string $entryIdentifier, string $data, array $tags = [], int $lifetime = null)
    {
        $this->backend->set($entryIdentifier, $data, $tags, $lifetime);
    }

    public function get(string $entryIdentifier)
    {
        return $this->backend->get($entryIdentifier);
    }

    public function has(string $entryIdentifier): bool
    {
        return $this->backend->has($entryIdentifier);
    }

    public function remove(string $entryIdentifier): bool
    {
        return $this->backend->remove($entryIdentifier);
    }

    public function flush()
    {
        $this->backend->flush();
    }

    public function collectGarbage()
    {
        $this->backend->collectGarbage();
    }

    public function flushByTag(string $tag): int
    {
        return $this->backend->flushByTag($tag);
    }

    public function findIdentifiersByTag(string $tag): array
    {
        return $this->backend->flushByTag($tag);
    }

    public function requireOnce(string $entryIdentifier)
    {
        return $this->backend->requireOnce($entryIdentifier);
    }

    public function current()
    {
        return $this->backend->current();
    }

    public function next()
    {
        $this->backend->next();
    }

    public function key()
    {
        return $this->backend->key();
    }

    public function valid()
    {
        return $this->backend->valid();
    }

    public function rewind()
    {
        $this->backend->rewind();
    }

    public function setCache(FrontendInterface $cache)
    {
        $this->backend->setCache($cache);
    }

    public function getPrefixedIdentifier(string $entryIdentifier): string
    {
        return $this->backend->getPrefixedIdentifier($entryIdentifier);
    }
}
