document.addEventListener("DOMContentLoaded", () => {
    fetch("../php/bewertung_reitlehrer.php")
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector("#dashboard table:nth-of-type(2) tbody");
            tbody.innerHTML = "";

            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" style="padding: 8px; font-style: italic; color: #777; text-align: center;">
                            Noch keine Bewertungen erhalten.
                        </td>
                    </tr>`;
                return;
            }

            data.forEach(b => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td style="padding: 8px;">${b.terminname}</td>
                    <td style="padding: 8px;">${b.datum}</td>
                    <td style="padding: 8px;">${b.vorname} ${b.nachname}</td>
                    <td style="padding: 8px;">${b.sterne} ⭐</td>
                    <td style="padding: 8px;">${b.kommentar}</td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(err => {
            console.error("Fehler beim Laden der Bewertungen:", err);
        });
});
