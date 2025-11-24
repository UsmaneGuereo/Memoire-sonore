const api_url = "http://localhost/omk_THyp_25-26_clone/api";
const identity = "NDfWQB6jwVQEyR5jKS5njoP2K3IHmQm8";
const credential = "6Wib53Kon9CcKzHeliWapZ9ad9rb9JK1";

const btnDemarrer = document.getElementById('btn-demarrer');
const btnStop = document.getElementById('btn-stop');
const btnTelecharger = document.getElementById('btn-telecharger');
const btnNouveau = document.getElementById('btn-nouveau');
const audioPlayer = document.getElementById('audio-player');
const audioContainer = document.getElementById('audio-container');
const state = document.getElementById('status');
const timer = document.getElementById('timer');
const alertErreur = document.getElementById('alert-erreur');
const messageErreur = document.getElementById('message-erreur');

let mediaRecorder;
let audioChunks = [];
let timerInterval;
let seconds = 0;

// Fonction pour formater le temps
function formatTime(sec) {
    const minutes = Math.floor(sec / 60);
    const secs = sec % 60;
    return `${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
}

// Démarrer l'enregistrement
btnDemarrer.addEventListener('click', async function () {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];
        seconds = 0;

        mediaRecorder.ondataavailable = (event) => {
            audioChunks.push(event.data);
        };

        mediaRecorder.onstop = () => {
            const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
            const audioUrl = URL.createObjectURL(audioBlob);
            audioPlayer.src = audioUrl;
            // btnTelecharger.href = audioUrl;
            audioContainer.classList.remove('d-none');

            // Arrêter toutes les pistes audio
            stream.getTracks().forEach(track => track.stop());
        };

        mediaRecorder.start();

        // Mise à jour de l'interface
        btnDemarrer.disabled = true;
        btnStop.disabled = false;
        timer.classList.remove('d-none');
        audioContainer.classList.add('d-none');
        state.innerHTML = '<span class="badge bg-danger">Enregistrement en cours...</span>';
        alertErreur.classList.add('d-none');

        // Démarrer le timer
        timerInterval = setInterval(() => {
            seconds++;
            timer.textContent = formatTime(seconds);
        }, 1000);

    } catch (error) {
        console.error('Erreur d\'accès au microphone:', error);
        messageErreur.textContent = 'Impossible d\'accéder au microphone. Vérifiez les permissions.';
        alertErreur.classList.remove('d-none');
    }
});

// Arrêter l'enregistrement
btnStop.addEventListener('click', function () {
    if (mediaRecorder && mediaRecorder.state !== 'inactive') {
        mediaRecorder.stop();
        clearInterval(timerInterval);
        
        // Mise à jour de l'interface
        btnDemarrer.disabled = false;
        btnStop.disabled = true;
        timer.classList.add('d-none');
        state.innerHTML = '<span class="badge bg-success">✓ Enregistrement terminé</span>';
    }
});

btnTelecharger.addEventListener('click', async function () {
    const id_audio = Math.floor(Math.random() * 200).toString();
    const id_temoignage = Math.floor(Math.random() * 200).toString();
    const fichier = new File(audioChunks, 'enregistrement.wav', { type: 'audio/wav' });
    const chemin_fichier = fichier.name;
    const duree = formatTime(seconds);
    const formatFile = fichier.type;

    const temp = await d3.json(`${api_url}/resource_templates`)
    .then(data => {
        return data.find(elt => elt['o:label'] === "Audio"); // Trouver le ressource template "Audio"
    });


    const ressource_template_id = temp['o:id'];
    const ressource_class_id = temp['o:resource_class']['o:id'];
    const id_audio_property = temp['o:resource_template_property'][0]['o:property']['o:id'];
    const chemin_fichier_property = temp['o:resource_template_property'][1]['o:property']['o:id'];
    const format_audio_property = temp['o:resource_template_property'][2]['o:property']['o:id'];
    const duree_audio_property = temp['o:resource_template_property'][3]['o:property']['o:id'];
    const transcription_audio_property = temp['o:resource_template_property'][4]['o:property']['o:id'];
    const id_temoignage_property = temp['o:resource_template_property'][5]['o:property']['o:id'];

    try {
            const data = {
                "o:resource_class": { "o:id": ressource_class_id }, // id de la resource class
                "o:resource_template": { "o:id": ressource_template_id }, // id du ressource template
                "o:Item": "https://memoiresonore.univ-paris8.fr/onto/jdc#:Audio",
                "https://memoiresonore.univ-paris8.fr/onto/jdc#:id_audio": [{ "type": "literal", property_id: id_audio_property, "@value": id_audio }],
                "https://memoiresonore.univ-paris8.fr/onto/jdc#:chemin_fichier": [{ "type": "literal", property_id: chemin_fichier_property, "@value": chemin_fichier }],
                "https://memoiresonore.univ-paris8.fr/onto/jdc#:duree": [{ "type": "literal", property_id: duree_audio_property, "@value": duree }],
                "https://memoiresonore.univ-paris8.fr/onto/jdc#:est_transcrit": [{ "type": "literal", property_id: transcription_audio_property, "@value": false }],
                "https://memoiresonore.univ-paris8.fr/onto/jdc#:id_temoignage": [{ "type": "literal", property_id: id_temoignage_property, "@value": id_temoignage }],
                "dcterms:format": [{ "type": "literal", property_id: format_audio_property, "@value": formatFile }]
            };

            const itemResponse = fetch(`${api_url}/items?key_identity=${identity}&key_credential=${credential}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });


            if (!(await itemResponse).ok) {
                throw new Error(`Erreur création item: ${(await itemResponse).status}`);
            }

            const mediaData = {
                "o:item": { "o:id": id_audio },
                "o:ingester": "upload",
                "file_index": 0,
                "o:is_public": true
            };

            const formData = new FormData();
            formData.append('data', JSON.stringify(mediaData));
            formData.append('file[0]', fichier);

            const mediaResponse = fetch(`${api_url}/media?key_identity=${identity}&key_credential=${credential}`, {
                method: 'POST',
                body: formData
            });

            console.log((await mediaResponse).Error)

            // if (!mediaResponse.ok) {
            //     throw new Error(`Erreur téléchargement de fichier: ${mediaResponse.status}`);
            // }

    } catch (error) {
        console.log(error)
    }
});

// Nouvel enregistrement
btnNouveau.addEventListener('click', function () {
    audioContainer.classList.add('d-none');
    timer.textContent = '00:00';
    seconds = 0;
    state.innerHTML = '<span class="badge bg-secondary">Prêt à enregistrer</span>';
});