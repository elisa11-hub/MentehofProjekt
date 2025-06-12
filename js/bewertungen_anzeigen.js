document.addEventListener("DOMContentLoaded", async function () {
    const container = document.getElementById("bewertungsContainer");

    const response = await fetch("bewertungen_anzeigen.php");
    const daten = await response.json();

    if (daten.length === 0) {
        container.innerHTML = "<p>Keine Bewertungen vorhanden.</p>";
        return;
    }

    daten.forEach(b => {
        const item = document.createElement("div");
        item.classList.add("bewertung");
        item.innerHTML = `
            <p><strong>Von Reitschüler ID:</strong> ${b.reitschueler_id}</p>
            <p><strong>Termin:</strong> ${b.termin_id}</p>
            <p><strong>Sterne:</strong> ${b.sterne}</p>
            <p><strong>Kommentar:</strong> ${b.kommentar}</p>
            <p><strong>Datum:</strong> ${b.datum}</p>
            <hr />
        `;
        container.appendChild(item);
    });
});
