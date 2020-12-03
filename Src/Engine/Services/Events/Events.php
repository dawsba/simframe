<?php


namespace Engine\Services\Events;


use Doctrine\Common\EventManager;
use Engine\Services\Services;

class Events extends Services
{
    
    public function __construct()
    {
        parent::setDescription('Doctrine Event Handler');
        parent::setServiceName('Events');
    }

    public function initialize()
    {
        $this->logger->info('Initializing Events');

        return new EventManager();
    }
}
