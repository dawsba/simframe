<?php


namespace Engine\Services\Logger;

use Engine\Services\Services;
use Monolog\Logger as Monolog;
use Monolog\Handler\StreamHandler;

class Logger extends Services
{    
    private $name = 'name';

    private $path = '';

    private $defaultLevel = Monolog::WARNING;

    /**
     * Logger constructor.
     * @param $logger
     * @param array|null $config
     */
    public function __construct(array $config = null)
    {
        parent::setDescription('Service class for Monolog');
        parent::setServiceName('Logger');
        
        if (null !== $config) {
            $this
                ->setName($config['name'])
                ->setPath($config['path'])
                ->setDefaultLevel(constant("Monolog\Logger::{$config['level']}"))
            ;
        }
    }

    /**
     * @return Monolog
     */
    public function initialize()
    {
        $log = new Monolog($this->getName());
        $log->pushHandler(
            new StreamHandler(
                $this->getPath(),
                $this->getDefaultLevel()
            )
        );

        $log->info('Initializing Logger');

        return $log;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Logger
     */
    public function setName(string $name): Logger
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Logger
     */
    public function setPath(string $path): Logger
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return int
     */
    public function getDefaultLevel(): int
    {
        return $this->defaultLevel;
    }

    /**
     * @param int $defaultLevel
     * @return Logger
     */
    public function setDefaultLevel(int $defaultLevel): Logger
    {
        $this->defaultLevel = $defaultLevel;

        return $this;
    }
}
