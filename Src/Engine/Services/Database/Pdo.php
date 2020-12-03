<?php

namespace Engine\Services\Database;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Engine\Services\Services;

class Pdo extends Services
{
    private $isDevMode = true;
    
    private $proxyDir = null;
    
    private $cache = null;
    
    private $useSimpleAnnotationReader = false;

    private $conn = array(
        'driver' => 'pdo_mysql',
        'user' => '',
        'password' => '',
        'dbname' => '',
        'host' => '',
    );

    /**
     * Pdo constructor.
     * @param array|null $conn
     */
    public function __construct(array $conn = null)
    {
        parent::setDescription('Database class based on PDO/Doctrine');
        parent::setServiceName('PDO');

        if (null !== $conn) {
            $this->setConn($conn);
        }
    }

    /**
     * @return $this|EntityManager
     * @throws ORMException
     */
    public function initialize()
    {
        $this->logger->info('Initializing PDO');
        
        $config = Setup::createAnnotationMetadataConfiguration(
            array(__DIR__."/src"),
            $this->isDevMode(),
            $this->getProxyDir(),
            $this->getCache(),
            $this->isUseSimpleAnnotationReader()
        );

        // obtaining the entity manager
        try {
            return EntityManager::create($this->getConn(), $config);
        }
        catch (ORMException $e) {
            $this->logger->error('Failed to connect to DB', (array) $e);
            
            return $this;
        }
    }

    /**
     * @return bool
     */
    public function isDevMode(): bool
    {
        return $this->isDevMode;
    }

    /**
     * @param bool $isDevMode
     * @return Pdo
     */
    public function setIsDevMode(bool $isDevMode): Pdo
    {
        $this->isDevMode = $isDevMode;

        return $this;
    }

    /**
     * @return null
     */
    public function getProxyDir()
    {
        return $this->proxyDir;
    }

    /**
     * @param null $proxyDir
     * @return Pdo
     */
    public function setProxyDir($proxyDir)
    {
        $this->proxyDir = $proxyDir;

        return $this;
    }

    /**
     * @return null
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param null $cache
     * @return Pdo
     */
    public function setCache($cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUseSimpleAnnotationReader(): bool
    {
        return $this->useSimpleAnnotationReader;
    }

    /**
     * @param bool $useSimpleAnnotationReader
     * @return Pdo
     */
    public function setUseSimpleAnnotationReader(bool $useSimpleAnnotationReader): Pdo
    {
        $this->useSimpleAnnotationReader = $useSimpleAnnotationReader;

        return $this;
    }

    /**
     * @return array
     */
    public function getConn(): array
    {
        return $this->conn;
    }

    /**
     * @param array $conn
     * @return Pdo
     */
    public function setConn(array $conn): Pdo
    {
        $this->conn = $conn;

        return $this;
    }
}
