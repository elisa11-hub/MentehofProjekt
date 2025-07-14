document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("kursForm");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const kurs = {};

        formData.forEach((value, key) => {
            kurs[key] = value;
        });

        // Leere Felder korrekt casten
        kurs.kosten = parseFloat(kurs.kosten || 0);
        kurs.dauer = parseInt(kurs.dauer || 0);
        kurs.max_teilnehmeranzahl = parseInt(kurs.max_teilnehmeranzahl || 0);
        kurs.min_reitlevel = parseInt(kurs.min_reitlevel || 0);

        fetch("../php/kurs_speichern_reitlehrer.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(kurs)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("Kurs gespeichert!");
                document.getElementById("kursModal").style.display = "none";
                form.reset();
                // Optional: Tabelle neu laden
            } else {
                alert("Fehler: " + (data.error || "Unbekannter Fehler"));
            }
        })
        .catch(err => {
            console.error("Fehler beim Speichern:", err);
            alert("Ein Fehler ist aufgetreten.");
        });
    });
});
