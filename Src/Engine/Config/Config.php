<?php

namespace Engine\Config;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Yaml\Yaml;

class Config
{    
    /** @var ArrayCollection $configArray */
    private $configArray;

    public function __construct()
    {
        $this->loadConfigYaml();
    }

    public function loadConfigYaml()
    {
        $configArray = Yaml::parseFile('./Src/Engine/Config/config.yaml');

        define(
            'isDev',
            ! array_key_exists('isDev', $configArray['config']) || false === $configArray['config']['isDev']
                ? false
                : true
        );

        $configToLoad = false !== isDev
            ? 'dev'
            : 'production'
        ;

        if ( ! array_key_exists($configToLoad, $configArray['config'])) {
            throw new \RuntimeException('UNABLE TO LOAD CONFIG - BAD OR MISSING DATA');
        }

        $this->setConfigArray(new ArrayCollection($configArray['config'][$configToLoad]));
    }

    /**
     * @param $key
     * @return ArrayCollection|array
     */
    public function getConfigKey($key)
    {
        if ( ! $this->getConfigArray()->containsKey($key)) {
            throw new \RuntimeException('NO KNOWN SERVICE IN CONFIG');
        }
        
        return $this->getConfigArray()->get($key);
    }

    /**
     * @return ArrayCollection
     */
    public function getConfigArray(): ArrayCollection
    {
        return $this->configArray;
    }

    /**
     * @param ArrayCollection $configArray
     * @return Config
     */
    public function setConfigArray(ArrayCollection $configArray): Config
    {
        $this->configArray = $configArray;
        
        return $this;
    }
}
