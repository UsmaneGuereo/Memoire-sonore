<?php
namespace MonModuleAudio\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Omeka\Api\Manager as ApiManager;
use Laminas\Http\Request;

class AudioController extends AbstractActionController
{
    protected $api;

    public function __construct(ApiManager $api)
    {
        $this->api = $api;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function transcribeAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $this->redirect()->toRoute('admin/monmodule-audio');
        }

        $file = $request->getFiles('audio_file');
        // Logique pour uploader le fichier et appeler AssemblyAI
        // Voir section suivante
    }
}
