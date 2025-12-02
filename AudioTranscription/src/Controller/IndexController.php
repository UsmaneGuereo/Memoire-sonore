<?php
namespace AudioTranscription\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use AudioTranscription\Service\TranscriptionService;

class IndexController extends AbstractActionController
{
    protected $services;

    public function __construct($services)
    {
        $this->services = $services;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function transcribeAction()
    {
        // Forcer la réponse JSON
        $this->getResponse()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        
        if (!$this->getRequest()->isPost()) {
            return new JsonModel([
                'status' => 'error',
                'message' => 'POST request required'
            ]);
        }

        try {
            $files = $this->getRequest()->getFiles();
            
            if (!isset($files['audio'])) {
                throw new \Exception('No audio file in request');
            }
            
            if ($files['audio']['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception('File upload error: ' . $files['audio']['error']);
            }

            $audioFile = $files['audio'];
            $tmpPath = $audioFile['tmp_name'];
            $filename = 'recording_' . date('Y-m-d_H-i-s') . '.wav';

            // Vérifier que le fichier existe
            if (!file_exists($tmpPath)) {
                throw new \Exception('Temporary file not found');
            }

            $settings = $this->services->get('Omeka\Settings');
            $apiKey = $settings->get('audio_transcription_api_url', 'http://localhost:5000');
            // $settings = $this->services->get('Omeka\Settings');
            // $apiKey = $settings->get('audio_transcription_api_key');

            if (empty($apiKey)) {
                throw new \Exception('OpenAI API key not configured. Please add it in module settings.');
            }

            $transcriptionService = new TranscriptionService($apiKey);
            $result = $transcriptionService->transcribe($tmpPath);
            
            // $transcriptionService = new TranscriptionService($apiKey);
            // $result = $transcriptionService->transcribe($tmpPath);

            $connection = $this->services->get('Omeka\Connection');
            $sql = "INSERT INTO audio_transcription (filename, transcription, created) VALUES (?, ?, NOW())";
            
            $connection->executeStatement($sql, [
                $filename,
                $result['text']
            ]);

            return new JsonModel([
                'status' => 'success',
                'transcription' => $result['text']
            ]);

        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            error_log('AudioTranscription Error: ' . $e->getMessage());
            error_log('AudioTranscription Trace: ' . $e->getTraceAsString());
            
            return new JsonModel([
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}