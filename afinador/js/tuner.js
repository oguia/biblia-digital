// Tuner Logic
(function() {
    const startBtn = document.getElementById('start-btn');
    const noteDisplay = document.getElementById('note');
    const frequencyDisplay = document.getElementById('frequency');
    const needle = document.getElementById('needle');
    const statusMsg = document.getElementById('status');
    const stringButtons = document.querySelectorAll('.string-buttons button');
    const stopSoundBtn = document.getElementById('stop-sound-btn');

    let audioContext;
    let analyser;
    let microphone;
    let requestAnimationFrameId;
    let isTunerRunning = false;
    let activeOscillator = null;

    // Guitar Strings Frequencies (Standard E Tuning)
    const strings = {
        'E2': 82.41,
        'A2': 110.00,
        'D3': 146.83,
        'G3': 196.00,
        'B3': 246.94,
        'E4': 329.63
    };

    // Note names
    const noteStrings = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"];

    startBtn.addEventListener('click', toggleTuner);

    stringButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const freq = parseFloat(btn.dataset.freq);
            playReferenceNote(freq, btn);
        });
    });

    stopSoundBtn.addEventListener('click', stopReferenceNote);

    function toggleTuner() {
        if (isTunerRunning) {
            stopTuner();
        } else {
            startTuner();
        }
    }

    async function startTuner() {
        if (!navigator.mediaDevices.getUserMedia) {
            alert('Seu navegador não suporta acesso ao microfone.');
            return;
        }

        try {
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });

            microphone = audioContext.createMediaStreamSource(stream);
            analyser = audioContext.createAnalyser();
            analyser.fftSize = 2048;
            microphone.connect(analyser);

            isTunerRunning = true;
            startBtn.textContent = "Parar Microfone";
            startBtn.classList.add('active');
            statusMsg.textContent = "Escutando...";

            updatePitch();
        } catch (err) {
            console.error(err);
            statusMsg.textContent = "Erro ao acessar microfone. Permita o acesso.";
        }
    }

    function stopTuner() {
        if (audioContext && audioContext.state !== 'closed') {
            audioContext.close();
        }
        if (requestAnimationFrameId) {
            cancelAnimationFrame(requestAnimationFrameId);
        }
        isTunerRunning = false;
        startBtn.textContent = "Iniciar Microfone";
        startBtn.classList.remove('active');
        statusMsg.textContent = "Clique em Iniciar para começar";
        noteDisplay.textContent = "--";
        frequencyDisplay.textContent = "0 Hz";
        needle.style.transform = `translateX(-50%) rotate(0deg)`;
        noteDisplay.classList.remove('in-tune');
    }

    function updatePitch() {
        if (!isTunerRunning) return;

        const bufferLength = analyser.fftSize;
        const buffer = new Float32Array(bufferLength);
        analyser.getFloatTimeDomainData(buffer);

        const frequency = autoCorrelate(buffer, audioContext.sampleRate);

        if (frequency > -1) {
            const note = getNote(frequency);
            const cents = getCents(frequency, note.frequency);

            displayNote(note, cents, frequency);
        }

        requestAnimationFrameId = requestAnimationFrame(updatePitch);
    }

    // Autocorrelation algorithm (YIN or basic)
    // Basic implementation for simplicity
    function autoCorrelate(buf, sampleRate) {
        let SIZE = buf.length;
        let rms = 0;

        for (let i = 0; i < SIZE; i++) {
            const val = buf[i];
            rms += val * val;
        }
        rms = Math.sqrt(rms / SIZE);

        if (rms < 0.01) // Not enough signal
            return -1;

        let r1 = 0, r2 = SIZE - 1, thres = 0.2;
        for (let i = 0; i < SIZE / 2; i++)
            if (Math.abs(buf[i]) < thres) { r1 = i; break; }
        for (let i = 1; i < SIZE / 2; i++)
            if (Math.abs(buf[SIZE - i]) < thres) { r2 = SIZE - i; break; }

        buf = buf.slice(r1, r2);
        SIZE = buf.length;

        let c = new Array(SIZE).fill(0);
        for (let i = 0; i < SIZE; i++)
            for (let j = 0; j < SIZE - i; j++)
                c[i] = c[i] + buf[j] * buf[j + i];

        let d = 0; while (c[d] > c[d + 1]) d++;
        let maxval = -1, maxpos = -1;
        for (let i = d; i < SIZE; i++) {
            if (c[i] > maxval) {
                maxval = c[i];
                maxpos = i;
            }
        }
        let T0 = maxpos;

        let x1 = c[T0 - 1], x2 = c[T0], x3 = c[T0 + 1];
        let a = (x1 + x3 - 2 * x2) / 2;
        let b = (x3 - x1) / 2;
        if (a) T0 = T0 - b / (2 * a);

        return sampleRate / T0;
    }

    function getNote(frequency) {
        const noteNum = 12 * (Math.log(frequency / 440) / Math.log(2));
        const roundedNoteNum = Math.round(noteNum) + 69;
        const noteName = noteStrings[roundedNoteNum % 12];

        // Calculate standard frequency for this note
        // f = 440 * 2^((n-69)/12)
        const noteFreq = 440 * Math.pow(2, (roundedNoteNum - 69) / 12);

        return {
            name: noteName,
            frequency: noteFreq
        };
    }

    function getCents(frequency, targetFrequency) {
        return 1200 * Math.log2(frequency / targetFrequency);
    }

    function displayNote(note, cents, frequency) {
        noteDisplay.textContent = note.name;
        frequencyDisplay.textContent = frequency.toFixed(1) + " Hz";

        // Needle rotation: -45deg to 45deg (representing -50 to +50 cents)
        // Clamp cents between -50 and 50
        const clampedCents = Math.max(-50, Math.min(50, cents));
        const rotation = (clampedCents / 50) * 45;

        needle.style.transform = `translateX(-50%) rotate(${rotation}deg)`;

        if (Math.abs(cents) < 5) {
            noteDisplay.classList.add('in-tune');
            statusMsg.textContent = "Afinado!";
            needle.style.backgroundColor = "var(--success-color)";
        } else {
            noteDisplay.classList.remove('in-tune');
            needle.style.backgroundColor = "var(--needle-color)";
            if (cents < 0) {
                statusMsg.textContent = "Aperte a corda (Muito grave)";
            } else {
                statusMsg.textContent = "Solte a corda (Muito agudo)";
            }
        }
    }

    // --- Sound Generation ---

    function playReferenceNote(freq, btnElement) {
        // Stop current sound if any
        stopReferenceNote();

        // Stop tuner if running to avoid feedback loop or confusion
        if (isTunerRunning) stopTuner();

        audioContext = new (window.AudioContext || window.webkitAudioContext)();
        activeOscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        activeOscillator.type = 'triangle'; // Closer to guitar than sine
        activeOscillator.frequency.value = freq;

        activeOscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        activeOscillator.start();

        // Visual feedback
        stringButtons.forEach(b => b.classList.remove('active'));
        if (btnElement) btnElement.classList.add('active');
        stopSoundBtn.style.display = 'inline-block';
    }

    function stopReferenceNote() {
        if (activeOscillator) {
            activeOscillator.stop();
            activeOscillator = null;
        }
        stringButtons.forEach(b => b.classList.remove('active'));
        stopSoundBtn.style.display = 'none';
    }

})();
