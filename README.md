# Mémoire Sonore


## Présentation

**Mémoire Sonore** est une plateforme qui permet de transformer des collections audio sur l'histoire en bases de connaissances interactives. En combinant reconnaissance vocale (Whisper), intelligence artificielle conversationnelle (Ollama) et gestion des métadonnées (Omeka S), elle permet d'explorer et d'interroger par exemple des archives sonores comme on dialoguerait avec un historien. Cette projet a pour cible chercheur, enseignant ou simple curieux. L'objectif est de pouvoir poser des questions en langage naturel et obtenir des réponses précises avec les extraits audio correspondants.


## Architecture

```mermaid
graph TB
    A[Sources Audio<br>Archives/Contemporain] --> B(Whisper<br>Transcription)
    B --> C[Omeka S<br>Gestion métadonnées]
    C --> D[AnythingLLM<br>Interface]
    D --> E[Ollama<br>LLM Local]
    E --> F[🎤 Utilisateur Final]
