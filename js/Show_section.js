function showSection(id) {
                // Alle Sections ausblenden
                document.querySelectorAll('.section').forEach(el => el.classList.remove('active'));

                // Die gewählte Section anzeigen
                const target = document.getElementById(id);
                if (target) {
                    target.classList.add('active');
                } else {
                    console.warn("Sektion nicht gefunden:", id);
                }
            }