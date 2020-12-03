<?php


namespace Engine\Services;


use Engine\Config\Config;
use Monolog\Logger;
use Psr\Log\NullLogger;

abstract class Services implements ServiceInterface
{
    /** @var Config $config */
    protected $config;
    
    protected $description = '';
    
    /** @var Logger|NullLogger  */
    protected $logger;
    
    protected $serviceName = '';

    public function initialize()
    {
        return self::class;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * @param string $serviceName
     */
    public function setServiceName(string $serviceName): void
    {
        $this->serviceName = $serviceName;
    }

    /**
     * @return Logger|NullLogger
     */
    public function getLogger(): ?Logger
    {
        return $this->logger;
    }

    /**
     * @param Logger|NullLogger $logger
     */
    public function setLogger(?Logger $logger): void
    {
        $this->logger = $logger;
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
}
