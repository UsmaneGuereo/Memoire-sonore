# Mémoire Sonore


## Présentation

**Mémoire Sonore** est une plateforme qui permet de transformer des collections audio sur l'histoire en bases de connaissances interactives. En combinant reconnaissance vocale (Whisper) et gestion des métadonnées (Omeka S), elle permet d'explorer et d'interroger par exemple des archives sonores comme on dialoguerait avec un historien. Cette projet a pour cible chercheur, enseignant, étudiant ou simple curieux. L'objectif est de pouvoir poser des questions en langage naturel et obtenir des réponses précises avec les extraits correspondants.


## Technologies utilises
**HTML**<br/>
**CSS**<br/>
**JAVASCRIPT**<br/>
**TYPESCRYPT**<br/>
**EXPRESSJS/FASTAPI/DJANGO**<br/>
**MYSQL**<br/>
**REST API**<br/>
**JSON/XML**<br/>
**GIT/GITHUB**<br/>

## DIagramme Entité-Relation


erDiagram
    UTILISATEUR {
        int id_utilisateur PK
        string nom
        string prenom
        string email
        string mot_de_passe
        string role
    }

    TEMOIGNAGE {
        int id_temoignage PK
        string titre
        string description
        date date_enregistrement
        string lieu
        int id_utilisateur FK
    }

    AUDIO {
        int id_audio PK
        string chemin_fichier
        string format
        float duree
        int id_temoignage FK
    }

    TRANSCRIPTION {
        int id_transcription PK
        text contenu
        string langue
        int id_audio FK
    }

    METADONNEE {
        int id_meta PK
        string cle
        string valeur
        int id_temoignage FK
    }

    QUESTION {
        int id_question PK
        string texte_question
        date date_question
        int id_utilisateur FK
    }

    REPONSE {
        int id_reponse PK
        text contenu
        date date_reponse
        int id_question FK
    }

    %% Relations
    UTILISATEUR ||--o{ TEMOIGNAGE : "ajoute"
    TEMOIGNAGE ||--o{ AUDIO : "contient"
    AUDIO ||--o{ TRANSCRIPTION : "est transcrit en"
    TEMOIGNAGE ||--o{ METADONNEE : "décrit par"
    UTILISATEUR ||--o{ QUESTION : "pose"
    QUESTION ||--o{ REPONSE : "reçoit"




## Architecture

```mermaid
graph TB
    A[Sources Audio<br>Archives/Contemporain] --> B(Whisper<br>Transcription)
    B --> C[Omeka S<br>Gestion métadonnées]
    C --> D[Plateforme Web]
    F[ Utilisateur Final] --> D
    D --> G[ Réponses ]
