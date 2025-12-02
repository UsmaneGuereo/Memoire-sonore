<?php
namespace WhisperTranscribe\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Http\Request;
use Laminas\Http\Client;
use Laminas\Json\Json;

class WhisperController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function transcribeAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $this->redirect()->toRoute('admin/whisper');
        }

        $file = $request->getFiles('audio_file');
        if (!$file || !$file['tmp_name']) {
            $this->messenger()->addError("Aucun fichier audio uploadé.");
            return $this->redirect()->toRoute('admin/whisper');
        }

        $client = new Client('http://localhost:8000/transcribe', [
            'adapter' => 'Laminas\Http\Client\Adapter\Curl',
        ]);

        $client->setFileUpload($file['tmp_name'], 'file');
        $response = $client->send();

        if (!$response->isSuccess()) {
            $this->messenger()->addError("Erreur lors de la transcription.");
            return $this->redirect()->toRoute('admin/whisper');
        }

        $result = Json::decode($response->getBody(), Json::TYPE_ARRAY);
        return new ViewModel([
            'transcription' => $result['transcription'] ?? "Erreur de transcription.",
        ]);
    }
}
