# 🎵 Mémoire Sonore

*De la préservation du passé à l'analyse du présent historique*

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Python 3.9+](https://img.shields.io/badge/python-3.9+-blue.svg)](https://www.python.org/downloads/)
[![Docker](https://img.shields.io/badge/docker-ready-blue.svg)](https://www.docker.com/)

## 📖 Présentation

**Mémoire Sonore** est une plateforme open-source innovante qui transforme des collections audio historiques et contemporaines en une base de connaissances interactive. En combinant reconnaissance vocale de pointe et intelligence artificielle conversationnelle, le projet permet d'explorer et d'interroger naturellement des archives sonores.

### 🌟 Ce que nous faisons différemment

- **🔗 Lien temporel unique** : Connexion entre archives historiques ET contenu contemporain
- **💬 Interface conversationnelle** : Posez des questions comme à un historien
- **🕒 Traitement temps réel** : Capture et transcription live d'événements historiques
- **🔒 Souveraineté des données** : Tout fonctionne localement, sans cloud externe

## 🏗 Architecture

```mermaid
graph TB
    A[Sources Audio<br>Archives/Contemporain/Temps-réel] --> B(Whisper<br>Transcription)
    B --> C[Omeka S<br>Gestion métadonnées]
    C --> D[AnythingLLM<br>Interface RAG]
    D --> E[Ollama<br>LLM Local]
    E --> F[🎤 Utilisateur Final]
