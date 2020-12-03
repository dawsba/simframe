<?php

namespace Engine\Services;


use Engine\Config\Config;
use Monolog\Logger;
use Psr\Log\NullLogger;

interface ServiceInterface
{
    public function initialize();
    
    public function getDescription();
    
    public function setDescription(string $description);
    
    public function setServiceName(string $name);
    
    public function getServiceName();
    
    public function getLogger();
    
    public function setLogger(?Logger $logger);
    
    public function getConfig();
    
    public function setConfig(Config $config);
}