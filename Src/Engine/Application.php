<?php

namespace Engine;

use Doctrine\Common\Collections\ArrayCollection;
use Engine\Config\Config;
use Engine\Services\Services;
use http\Exception;
use http\Exception\RuntimeException;
use Monolog\Logger;
use Psr\Log\NullLogger;
use Symfony\Component\Yaml\Yaml;

class Application
{
    /** @var Config $config */
    private $config;
    
    /** @var Logger|NullLogger */
    private $logger;
    
    private $services = array();
    
    private $descriptions = array();
    
    private $initialized = array();
    
    const primaryLogName = 'log';
    
    const InitializeMethod = 'initialize';
    
    const DescriptionMethod = 'getDescription';
    
    const serviceExists = 'Service Exists';
    
    const serviceDoesNotExist = 'Service Does Not Exist';

    /**
     * Application constructor.
     */
    public function __construct()
    {
        $this->setConfig(new Config());
        
        $this->services = new ArrayCollection();
        $this->descriptions = new ArrayCollection();
        $this->initialized = new ArrayCollection();

        // Registers the logger service
        $this->getLogger();
    }

    /**
     * @param string $name
     * @param Services $service
     */
    public function register(string $name, $service): void
    {
        if ($this->doesServiceExistByName($name)) {
            throw new RuntimeException(self::serviceExists);
        }

        if ( 
            ! $service instanceof \Engine\Services\Logger\Logger 
            && $this->doesServiceMethodExist($service, 'setLogger')
        ) {
            $service->setLogger($this->getLogger());
        }
        
        $this->services->set($name, $service);
        
        if ($this->doesServiceMethodExist($service, 'setConfig')) {
            $service->setConfig($this->getConfig());
        }
        
        $initialized = true;
        if ($this->doesServiceMethodExist($service, self::InitializeMethod)) {
            $initialized = false;
        }
        $this->initialized->set($name, $initialized);

        if ($this->doesServiceMethodExist($service, self::DescriptionMethod)) {
            $this->descriptions->set($name, $service->getDescription());
        }
    }
    
    public function registerServicesFromYaml($yaml)
    {
        try {
            $servicesArray = new ArrayCollection(Yaml::parseFile($yaml));
            
            if ($servicesArray->containsKey('services')) {
                foreach ($servicesArray->get('services') as $serviceName => $serviceVars) {
                    
                }
            }
        }
        catch (Exception $e) {
            $this->log->error('Error in register:' . $e->getMessage(), (array) $e);
        }


    }

    /**
     * @param $service
     * @param string $method
     * @return bool
     */
    public function doesServiceMethodExist($service, string $method)
    {
        if (is_object($service) && method_exists($service, $method)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isServiceInitialized($name)
    {
        if ($this->initialized->containsKey($name)) {
            return $this->initialized->get($name);
        }
        
        return false;
    }

    /**
     * @param string $name
     */
    public function initialize($name)
    {
        if ( ! $this->isServiceInitialized($name) && $this->doesServiceExistByName($name)) {
            $service = $this->services->get($name);
            if ($this->doesServiceMethodExist($service, self::InitializeMethod)) {
                $this->services->set($name, $service->initialize());
            }
        }
    }

    /**
     * @return null
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param string $name
     */
    public function deregister($name): void
    {
        if ( ! $this->doesServiceExistByName($name)) {
            throw new RuntimeException(self::serviceDoesNotExist);
        }
        
        $this->services->remove($name);
        $this->initialized->remove($name);
        $this->descriptions->remove($name);
    }

    private function doesServiceExistByName($serviceName)
    {
        if ($this->services->containsKey($serviceName)) {
            return true;
        }

        return false;
    }

    /**
     * @param $name
     * @return Services
     */
    public function __get($name)
    {
        if ( ! $this->doesServiceExistByName($name)) {
            throw new RuntimeException(self::serviceDoesNotExist);
        }
        
        $this->initialize($name);

        return $this->services->get($name);
    }

    /**
     * @return array
     */
    public function getDescriptions(): array
    {
        return $this->descriptions;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @param Config $config
     */
    public function setConfig(Config $config): void
    {
        $this->config = $config;
    }

    /**
     * @return Logger|NullLogger|\Engine\Services\Logger\Logger
     */
    public function getLogger(): ?Logger
    {
        if ( ! isset($this->logger) || ! is_object($this->logger)) {
            $this->register(
                self::primaryLogName,
                new \Engine\Services\Logger\Logger(
                    $this->getConfig()->getConfigKey(self::primaryLogName)
                )
            );

            $this->setLogger($this->log);
        }

        return $this->logger;
    }

    /**
     * @param Logger|NullLogger $logger
     */
    public function setLogger(?Logger $logger): void
    {
        $this->logger = $logger;
    }
}
