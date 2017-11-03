<?php

namespace Ruvents\AdminBundle\Config;

use Psr\SimpleCache\CacheInterface;
use Ruvents\AdminBundle\Config\Model\Config;
use Ruvents\AdminBundle\Config\Pass\PassInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

final class Manager implements CacheWarmerInterface
{
    private const CACHE_KEY = 'config';

    /**
     * @var array
     */
    private $data;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var iterable|PassInterface[]
     */
    private $passes;

    /**
     * @var null|Config
     */
    private $config;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @param array                    $data
     * @param CacheInterface           $cache
     * @param iterable|PassInterface[] $passes
     * @param bool                     $debug
     */
    public function __construct(array $data, CacheInterface $cache, iterable $passes = [], bool $debug)
    {
        $this->data = $data;
        $this->cache = $cache;
        $this->passes = $passes;
        $this->debug = $debug;
    }

    public function getConfig(): Config
    {
        if (null !== $this->config) {
            return $this->config;
        }

        if ($this->cache->has(self::CACHE_KEY)) {
            /** @var Config $config */
            $config = $this->cache->get(self::CACHE_KEY);

            if ($this->debug || $config->hash !== $this->getDataHash($this->data)) {
                $config = $this->buildAndSaveConfig();
            }
        } else {
            $config = $this->buildAndSaveConfig();
        }

        return $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function warmUp($cacheDir)
    {
        $this->buildAndSaveConfig();
    }

    private function buildAndSaveConfig(): Config
    {
        $config = new Config();
        $config->hash = $this->getDataHash($this->data);

        foreach ($this->passes as $pass) {
            $pass->process($config, $this->data);
        }

        $this->cache->set(self::CACHE_KEY, $config);

        return $config;
    }

    private function getDataHash(array $data): string
    {
        return sha1(serialize($data));
    }
}
