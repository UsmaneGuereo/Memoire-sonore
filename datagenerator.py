import csv
import os
from datetime import datetime

class CSVGenerator:
    def __init__(self):
        # Création du dossier s'il n'existe pas
        self.data_dir = "assets/csv/"
        os.makedirs(self.data_dir, exist_ok=True)
    
    def generate_utilisateurs(self):
        data = [
            {
                'id_utilisateur': 1,
                'nom': 'Dupont',
                'prenom': 'Jean',
                'email': 'jean.dupont@email.com',
                'role': 'admin'
            },
            {
                'id_utilisateur': 2,
                'nom': 'Martin',
                'prenom': 'Marie',
                'email': 'marie.martin@email.com',
                'role': 'contributeur'
            },
            {
                'id_utilisateur': 3,
                'nom': 'Bernard',
                'prenom': 'Pierre',
                'email': 'pierre.bernard@email.com',
                'role': 'visiteur'
            }
        ]
        
        self._write_csv('utilisateurs.csv', data)
    
    def generate_temoignages(self):
        data = [
            {
                'id_temoignage': 1,
                'titre': 'Souvenirs de guerre',
                'description': 'Témoignage sur la Seconde Guerre mondiale',
                'date_enregistrement': '2023-05-15',
                'lieu': 'Paris',
                'id_utilisateur': 2
            },
            {
                'id_temoignage': 2,
                'titre': 'Histoire familiale',
                'description': 'Récit de la tradition familiale',
                'date_enregistrement': '2023-06-20',
                'lieu': 'Lyon',
                'id_utilisateur': 2
            },
            {
                'id_temoignage': 3,
                'titre': 'Mémoire ouvrière',
                'description': 'Témoignage sur les usines des années 60',
                'date_enregistrement': '2023-07-10',
                'lieu': 'Marseille',
                'id_utilisateur': 1
            }
        ]
        
        self._write_csv('temoignages.csv', data)
    
    def generate_audios(self):
        data = [
            {
                'id_audio': 1,
                'chemin_fichier': '/audio/temoignage1.mp3',
                'format': 'mp3',
                'duree': 1250.5,
                'est_transcrit': True,
                'id_temoignage': 1
            },
            {
                'id_audio': 2,
                'chemin_fichier': '/audio/temoignage2.wav',
                'format': 'wav',
                'duree': 980.2,
                'est_transcrit': True,
                'id_temoignage': 2
            },
            {
                'id_audio': 3,
                'chemin_fichier': '/audio/temoignage3.mp3',
                'format': 'mp3',
                'duree': 1560.8,
                'est_transcrit': False,
                'id_temoignage': 3
            }
        ]
        
        self._write_csv('audios.csv', data)
    
    def generate_transcriptions(self):
        data = [
            {
                'id_transcription': 1,
                'contenu': 'Je me souviens de cette période difficile où nous devions nous cacher chaque fois que les sirènes retentissaient. Les nuits étaient longues et froides dans les abris.',
                'langue': 'fr',
                'date_transcription': '2023-05-20',
                'id_audio': 1
            },
            {
                'id_transcription': 2,
                'contenu': 'Notre famille avait pour tradition de se réunir chaque dimanche pour le déjeuner. Grand-mère préparait toujours son fameux poulet rôti et nous racontait des histoires de son enfance.',
                'langue': 'fr',
                'date_transcription': '2023-06-25',
                'id_audio': 2
            }
        ]
        
        self._write_csv('transcriptions.csv', data)
    
    def generate_metadonnees(self):
        data = [
            {
                'id_meta': 1,
                'cle': 'theme',
                'valeur': 'Guerre',
                'id_temoignage': 1
            },
            {
                'id_meta': 2,
                'cle': 'periode',
                'valeur': '1940-1945',
                'id_temoignage': 1
            },
            {
                'id_meta': 3,
                'cle': 'theme',
                'valeur': 'Famille',
                'id_temoignage': 2
            },
            {
                'id_meta': 4,
                'cle': 'theme',
                'valeur': 'Travail',
                'id_temoignage': 3
            },
            {
                'id_meta': 5,
                'cle': 'lieu_detail',
                'valeur': 'Usine Renault',
                'id_temoignage': 3
            }
        ]
        
        self._write_csv('metadonnees.csv', data)
    
    def generate_questions(self):
        data = [
            {
                'id_question': 1,
                'texte_question': 'Pouvez-vous préciser la date exacte de cet événement ?',
                'date_question': '2023-05-22',
                'id_utilisateur': 3,
                'id_transcription': 1
            },
            {
                'id_question': 2,
                'texte_question': 'Quelle était votre situation familiale à cette époque ?',
                'date_question': '2023-05-23',
                'id_utilisateur': 1,
                'id_transcription': 1
            },
            {
                'id_question': 3,
                'texte_question': 'Comment cette tradition familiale a-t-elle évolué au fil des générations ?',
                'date_question': '2023-06-27',
                'id_utilisateur': 3,
                'id_transcription': 2
            }
        ]
        
        self._write_csv('questions.csv', data)
    
    def generate_reponses(self):
        data = [
            {
                'id_reponse': 1,
                'contenu': "C'était au printemps 1944, je me souviens particulièrement des cerisiers en fleur dans le jardin. La date exacte serait autour du 15 avril.",
                'date_reponse': '2023-05-24',
                'id_question': 1
            },
            {
                'id_reponse': 2,
                'contenu': "Nous étions une famille de 5 personnes : mes parents, mes deux frères et moi. Mon père travaillait à l'usine et ma mère s'occupait de la maison.",
                'date_reponse': '2023-05-25',
                'id_question': 2
            },
            {
                'id_reponse': 3,
                'contenu': "La tradition s'est modernisée avec le temps. Aujourd'hui, ce sont les enfants qui organisent parfois le repas, et nous accueillons aussi des amis. Mais l'esprit de partage et de transmission est resté le même.",
                'date_reponse': '2023-06-28',
                'id_question': 3
            }
        ]
        
        self._write_csv('reponses.csv', data)
    
    def _write_csv(self, filename, data):
        """Écrit les données dans un fichier CSV"""
        filepath = os.path.join(self.data_dir, filename)
        try:
            with open(filepath, 'w', newline='', encoding='utf-8') as csvfile:
                if data:
                    fieldnames = data[0].keys()
                    writer = csv.DictWriter(csvfile, fieldnames=fieldnames)
                    writer.writeheader()
                    writer.writerows(data)
                    print(f"✓ Fichier {filename} généré avec {len(data)} entrées")
        except Exception as e:
            print(f"✗ Erreur lors de l'écriture de {filename}: {e}")

def main():
    """Fonction principale pour générer tous les fichiers"""
    print("Début de la génération des fichiers CSV...")
    
    # Créer le générateur
    generator = CSVGenerator()
    
    # Générer tous les fichiers
    generator.generate_utilisateurs()
    generator.generate_temoignages()
    generator.generate_audios()
    generator.generate_transcriptions()
    generator.generate_metadonnees()
    generator.generate_questions()
    generator.generate_reponses()
    
    print("✓ Tous les fichiers CSV ont été générés avec succès!")

if __name__ == "__main__":
    main()