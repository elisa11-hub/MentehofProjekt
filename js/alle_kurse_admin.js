document.addEventListener("DOMContentLoaded", () => {
    // Wenn der Benutzer zur Kurs-Section navigiert
    const kursTab = document.querySelector('a[href="#"]');
    kursTab?.addEventListener("click", () => {
        if (kursTab.textContent.includes("Termine verwalten")) {
            ladeKurse();
        }
    });
});

function ladeKurse() {
    fetch("../php/get_kurse.php") // Pfad ggf. anpassen
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector("#termin tbody");
            tbody.innerHTML = "";

            if (data.length === 0) {
                tbody.innerHTML = `<tr style="height: 3rem; background-color: #fff7ef;">
                    <td colspan="10" style="font-style: italic; color: #777; text-align: center;">Noch keine Kurse eingetragen.</td>
                </tr>`;
                return;
            }

            data.forEach(kurs => {
                const row = document.createElement("tr");
                row.style.height = "3rem";
                row.style.backgroundColor = "#fff7ef";
                row.innerHTML = `
                    <td style="padding-right: 2rem;">${kurs.kursname}</td>
                    <td style="padding-right: 2rem;">${kurs.bezeichnung}</td>
                    <td style="padding-right: 2rem;">${kurs.min_reitlevel}</td>
                    <td style="padding-right: 2rem;">${kurs.kosten} €</td>
                    <td style="padding-right: 2rem;">${kurs.datum}</td>
                    <td style="padding-right: 2rem;">${kurs.uhrzeit}</td>
                    <td style="padding-right: 2rem;">${kurs.dauer} Min</td>
                    <td style="padding-right: 2rem;">${kurs.ort}</td>
                    <td style="padding-right: 2rem;">${kurs.max_teilnehmerzahl}</td>
                    <td style="padding-right: 2rem;">${kurs.reitlehrer_idreitlehrer}</td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(err => {
            console.error("Fehler beim Laden der Kurse:", err);
        });
}
