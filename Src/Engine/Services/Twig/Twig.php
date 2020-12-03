<?php


namespace Engine\Services\Twig;


use Engine\Services\Services;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig extends Services
{
    private $templatePath = './Design/Templates';

    public function __construct($templatePath = null)
    {
        parent::setDescription('Twig service class');
        parent::setServiceName('Twig');

        if (null !== $templatePath) $this->templatePath = $templatePath;
    }

    public function initialize()
    {
        $this->logger->info('Initializing Twig');

        return new Environment(new FilesystemLoader($this->templatePath));
    }

    /**
     * @param string $templatePath
     */
    public function setTemplatePath(string $templatePath): void
    {
        $this->templatePath = $templatePath;
    }
}
