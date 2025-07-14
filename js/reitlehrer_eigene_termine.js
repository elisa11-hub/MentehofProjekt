document.addEventListener("DOMContentLoaded", () => {
    fetch("../php/termine_fuer_reitlehrer.php")
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector("#kurs-tabelle");
            tbody.innerHTML = "";

            if (!data.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" style="text-align:center; font-style: italic; color: #777;">
                            Noch keine Termine eingetragen.
                        </td>
                    </tr>`;
                return;
            }

            data.forEach(kurs => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${kurs.datum}</td>
                    <td>${kurs.uhrzeit}</td>
                    <td>${kurs.ort}</td>
                    <td>${kurs.max_teilnehmeranzahl}</td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => {
            console.error("Fehler beim Laden der Termine:", error);
        });
});

