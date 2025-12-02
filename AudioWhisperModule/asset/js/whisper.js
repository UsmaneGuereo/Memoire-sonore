let mediaRecorder;
let audioChunks = [];

document.getElementById('startRecord').addEventListener('click', async function() {
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
    mediaRecorder = new MediaRecorder(stream);
    mediaRecorder.start();
    document.getElementById('startRecord').disabled = true;
    document.getElementById('stopRecord').disabled = false;

    mediaRecorder.ondataavailable = event => {
        audioChunks.push(event.data);
    };

    mediaRecorder.onstop = () => {
        const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
        const formData = new FormData();
        formData.append('audio_file', audioBlob, 'recording.wav');
        const audioUrl = URL.createObjectURL(audioBlob);
        const audio = new Audio(audioUrl);
        audio.play();

        // Soumettre automatiquement le formulaire
        const form = document.getElementById('transcribeForm');
        const input = document.getElementById('audioBlob');
        input.value = audioBlob;
        // Pour soumission automatique, utiliser fetch ou soumettre le formulaire
        // Ici, on simule la soumission via un input hidden, mais en vrai il faut utiliser FormData + fetch
        // Voir note ci-dessous
    };
});

document.getElementById('stopRecord').addEventListener('click', function() {
    mediaRecorder.stop();
    document.getElementById('startRecord').disabled = false;
    document.getElementById('stopRecord').disabled = true;
});

// Soumission du formulaire
document.getElementById('transcribeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append('audio_file', audioChunks[0], 'recording.wav');

    fetch('<?= $this->url('admin/whisper', ['action' => 'transcribe']) ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(html => {
        document.open();
        document.write(html);
        document.close();
    })
    .catch(error => {
        alert("Erreur : " + error);
    });
});
