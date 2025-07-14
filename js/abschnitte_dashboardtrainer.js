function showSection(id) {
    // Alle Abschnitte ausblenden
    document.querySelectorAll('.section').forEach(section => {
        section.style.display = 'none';
    });

    // Gewählten Abschnitt einblenden
    const target = document.getElementById(id);
    if (target) {
        target.style.display = 'block';
    }
}