<?php
namespace AudioRecorder\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function recordAction()
    {
        $form = $this->getForm(AudioRecorderForm::class);
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            
            if ($form->isValid()) {
                // Traiter l'audio
                $audioData = $data['audio_data'];
                $this->saveAudio($audioData, $data);
                
                $this->messenger()->addSuccess('Audio enregistré avec succès');
                return $this->redirect()->toRoute('admin/audio-recorder');
            }
        }
        
        return new ViewModel([
            'form' => $form
        ]);
    }
    
    private function saveAudio($base64Audio, $data)
    {
        // Décoder le base64
        $audioData = explode(',', $base64Audio);
        $audioContent = base64_decode($audioData[1]);
        
        // Générer un nom de fichier unique
        $filename = 'audio_' . time() . '.wav';
        $filepath = OMEKA_PATH . '/files/audio/' . $filename;
        
        // Sauvegarder le fichier
        file_put_contents($filepath, $audioContent);
        
        // Créer un media Omeka si nécessaire
        // ... logique pour attacher à un item
    }
}