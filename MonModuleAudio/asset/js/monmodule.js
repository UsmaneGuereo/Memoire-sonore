document.addEventListener('DOMContentLoaded', function() {
    let mediaRecorder;
    let audioChunks = [];

    document.getElementById('startRecord').addEventListener('click', async function() {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        mediaRecorder.start();

        mediaRecorder.ondataavailable = event => {
            audioChunks.push(event.data);
        };

        mediaRecorder.onstop = () => {
            const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
            const formData = new FormData();
            formData.append('audio_file', audioBlob, 'recording.wav');

            fetch('/admin/monmodule-audio/transcribe', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('transcription').textContent = data.transcription;
            });
        };
    });

    document.getElementById('stopRecord').addEventListener('click', function() {
        mediaRecorder.stop();
    });
});
