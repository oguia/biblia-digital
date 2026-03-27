document.addEventListener('DOMContentLoaded', function() {

    // --- Transpose Logic ---
    const notes = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'];
    const notesFlat = ['C', 'Db', 'D', 'Eb', 'E', 'F', 'Gb', 'G', 'Ab', 'A', 'Bb', 'B'];

    // Map flats to sharps for calculation
    const flatToSharp = {
        'Db': 'C#', 'Eb': 'D#', 'Gb': 'F#', 'Ab': 'G#', 'Bb': 'A#',
        'Cbm': 'Bm', 'Dbm': 'C#m', 'Gbm': 'F#m', 'Abm': 'G#m', 'Bbm': 'A#m' // and so on... simplification
    };

    const btnUp = document.getElementById('btn-transpose-up');
    const btnDown = document.getElementById('btn-transpose-down');
    const chordContainer = document.getElementById('chord-text');
    const keyDisplay = document.getElementById('key');

    if (btnUp && btnDown && chordContainer) {
        btnUp.addEventListener('click', () => transpose(1));
        btnDown.addEventListener('click', () => transpose(-1));
    }

    function transpose(semitones) {
        // Update Key Display
        if (keyDisplay) {
            let currentKey = keyDisplay.textContent.trim();
            let newKey = shiftNote(currentKey, semitones);
            keyDisplay.textContent = newKey;
        }

        // Regex to find chords.
        // Matches: Start of line or space + Note (A-G) + optional (#/b) + optional suffixes (m, 7, sus, etc) + Space or End of Line
        // This is tricky inside a <pre> with lyrics mixed.
        // Cifra Club typically puts chords in <b> tags. The scraper preserves them.

        const bTags = chordContainer.querySelectorAll('b');

        if (bTags.length > 0) {
            // Scraper preserved <b> tags, easier!
            bTags.forEach(el => {
                el.textContent = shiftNote(el.textContent, semitones);
            });
        } else {
            // Fallback: Regex replace on text content (riskier)
            // Ideally the scraper should ensure <b> tags.
            // Let's rely on the scraper doing its job.
        }
    }

    function shiftNote(note, semitones) {
        // Extract root note and suffix
        // Matches A, A#, Ab, but keeps 'm', '7', etc separate
        let rootMatch = note.match(/^([A-G][#b]?)(.*)/);
        if (!rootMatch) return note;

        let root = rootMatch[1];
        let suffix = rootMatch[2];

        // Normalize flat to sharp for index finding
        if (root.includes('b')) {
            // Simple mapping or find in flat array
            let idx = notesFlat.indexOf(root);
            if (idx === -1) idx = notes.indexOf(root); // Fallback

            let newIdx = (idx + semitones) % 12;
            if (newIdx < 0) newIdx += 12;

            // Return flat if originally flat, or logic preference?
            // Let's stick to sharps for simplicity or check context.
            // Simple: Return valid note from notes array.
            return notes[newIdx] + suffix;
        } else {
            let idx = notes.indexOf(root);
            if (idx === -1) return note;

            let newIdx = (idx + semitones) % 12;
            if (newIdx < 0) newIdx += 12;

            return notes[newIdx] + suffix;
        }
    }

    // --- Auto Scroll Logic ---
    const btnScroll = document.getElementById('btn-autoscroll');
    let scrollInterval = null;
    let scrollSpeed = 1; // pixel per tick

    if (btnScroll) {
        btnScroll.addEventListener('click', toggleScroll);
    }

    function toggleScroll() {
        if (scrollInterval) {
            clearInterval(scrollInterval);
            scrollInterval = null;
            btnScroll.classList.remove('active');
            btnScroll.style.background = '';
        } else {
            btnScroll.classList.add('active');
            btnScroll.style.background = 'var(--primary-red)'; // Visual feedback
            scrollInterval = setInterval(() => {
                window.scrollBy(0, 1);
                // Stop at bottom
                if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                    clearInterval(scrollInterval);
                    scrollInterval = null;
                    btnScroll.classList.remove('active');
                    btnScroll.style.background = '';
                }
            }, 50); // Speed
        }
    }

});
