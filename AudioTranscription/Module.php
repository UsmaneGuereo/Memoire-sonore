<?php
namespace AudioTranscription;

use Omeka\Module\AbstractModule;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\Mvc\Controller\AbstractController;

class Module extends AbstractModule
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function install(ServiceLocatorInterface $services)
    {
        $connection = $services->get('Omeka\Connection');

        $sql = "
            CREATE TABLE IF NOT EXISTS audio_transcription (
                id INT AUTO_INCREMENT NOT NULL,
                filename VARCHAR(255) NOT NULL,
                transcription LONGTEXT NOT NULL,
                created DATETIME NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
        ";

        $connection->exec($sql);
    }

    public function uninstall(ServiceLocatorInterface $services)
    {
        $connection = $services->get('Omeka\Connection');
        $connection->exec('DROP TABLE IF EXISTS audio_transcription');
    }

    public function getConfigForm(PhpRenderer $renderer)
    {
        $services = $this->getServiceLocator();
        $settings = $services->get('Omeka\Settings');
        $apiUrl = $settings->get('audio_transcription_api_url', 'http://localhost:5000');

        return $renderer->render('audio-transcription/config-form', [
            'apiUrl' => $apiUrl,
        ]);
    }

    public function handleConfigForm(AbstractController $controller)
    {
        $services = $this->getServiceLocator();
        $settings = $services->get('Omeka\Settings');
        $form = $controller->params()->fromPost();

        $settings->set('audio_transcription_api_url', $form['api_url']);

        return true;
    }
}