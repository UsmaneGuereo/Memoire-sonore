<?php
namespace MonModuleAudio;

use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Omeka\Module\AbstractModule;

class Module extends AbstractModule
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(Event $event)
    {
        parent::onBootstrap($event);
        // Ajouter des assets JS/CSS si nécessaire
    }
}
