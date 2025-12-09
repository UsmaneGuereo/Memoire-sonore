from flask import Flask, request, jsonify
from flask_cors import CORS
import whisper
import os
import tempfile
import logging

app = Flask(__name__)
CORS(app)  # Permettre les requêtes cross-origin

# Configuration du logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Charger le modèle Whisper au démarrage (une seule fois)
logger.info("Chargement du modèle Whisper...")
# Options: tiny, base, small, medium, large
# tiny = rapide mais moins précis
# base = bon compromis
# large = très précis mais lent
model = whisper.load_model("base")
logger.info("Modèle Whisper chargé avec succès!")

@app.route('/verification', methods=['GET'])
def health():
    """Endpoint pour vérifier que l'API fonctionne"""
    return jsonify({
        'status': 'ok',
        'model': 'whisper'
    })

@app.route('/transcribe', methods=['POST'])
def transcribe():
    """Endpoint pour transcrire un fichier audio"""
    try:
        # Vérifier qu'un fichier a été envoyé
        if 'audio' not in request.files:
            return jsonify({
                'status': 'error',
                'message': 'No audio file provided'
            }), 400
        
        audio_file = request.files['audio']
        
        if audio_file.filename == '':
            return jsonify({
                'status': 'error',
                'message': 'Empty filename'
            }), 400
        
        # Sauvegarder temporairement le fichier
        with tempfile.NamedTemporaryFile(delete=False, suffix='.wav') as temp_file:
            temp_path = temp_file.name
            audio_file.save(temp_path)
            logger.info(f"Fichier audio sauvegardé: {temp_path}")
        
        try:
            # Transcrire avec Whisper
            logger.info("Début de la transcription...")
            result = model.transcribe(temp_path, language='fr')  # Spécifier français ou 'auto'
            
            transcription = result['text']
            language = result.get('language', 'unknown')
            
            logger.info(f"Transcription : {transcription}")
            logger.info(f"Transcription réussie: {len(transcription)} caractères")
            
            return jsonify({
                'status': 'success',
                'text': transcription,
                'language': language
            })
            
        finally:
            # Nettoyer le fichier temporaire
            if os.path.exists(temp_path):
                os.remove(temp_path)
                logger.info(f"Fichier temporaire supprimé: {temp_path}")
    
    except Exception as e:
        logger.error(f"Erreur lors de la transcription: {str(e)}")
        return jsonify({
            'status': 'error',
            'message': str(e)
        }), 500

if __name__ == '__main__':
    # Démarrer le serveur sur le port 5000
    # host='0.0.0.0' permet d'accepter les conne<xions de n'importe quelle IP
    app.run(host='0.0.0.0', port=5000, debug=False)