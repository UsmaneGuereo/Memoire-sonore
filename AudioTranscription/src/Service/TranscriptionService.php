<?php
namespace AudioTranscription\Service;

class TranscriptionService
{
    protected $apiUrl;
    
    public function __construct($apiUrl = 'http://localhost:5000')
    {
        $this->apiUrl = $apiUrl;
    }

    public function transcribe($audioFilePath)
    {
        // Vérifier que le fichier existe
        if (!file_exists($audioFilePath)) {
            throw new \Exception('Audio file not found: ' . $audioFilePath);
        }
        
        // Vérifier que cURL est disponible
        if (!function_exists('curl_init')) {
            throw new \Exception('cURL extension is not enabled');
        }
        
        error_log('Whisper Local: Starting transcription...');
        
        $ch = curl_init();
        
        // Créer le fichier cURL
        if (class_exists('CURLFile')) {
            $cfile = new \CURLFile($audioFilePath, 'audio/wav', 'audio.wav');
        } else {
            $cfile = '@' . $audioFilePath;
        }
        
        $postData = [
            'audio' => $cfile
        ];

        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl . '/transcribe',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_TIMEOUT => 300, // 5 minutes
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            error_log('Whisper Local: cURL error - ' . $error);
            throw new \Exception('Connection error: ' . $error . '. Is Whisper API running on ' . $this->apiUrl . '?');
        }
        
        curl_close($ch);

        error_log('Whisper Local: Response HTTP code: ' . $httpCode);

        if ($httpCode !== 200) {
            throw new \Exception('Whisper API error (HTTP ' . $httpCode . '): ' . $response);
        }
        
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Failed to parse JSON response: ' . json_last_error_msg());
        }
        
        if (!isset($data['text'])) {
            throw new \Exception('No transcription text in response');
        }
        
        error_log('Whisper Local: Transcription successful');
        
        return [
            'text' => $data['text']
        ];
    }
}