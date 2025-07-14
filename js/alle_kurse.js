document.addEventListener("DOMContentLoaded", () => {
    fetch("../php/alle_kurse.php")
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector("#alle-kurse tbody");
            tbody.innerHTML = "";

            data.forEach(kurs => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${kurs.kursname}</td>
                    <td>${kurs.bezeichnung}</td>
                    <td>${kurs.min_reitlevel}</td>
                    <td>${kurs.kosten} €</td>
                    <td>${kurs.datum}</td>
                    <td>${kurs.uhrzeit}</td>
                    <td>${kurs.dauer} min</td>
                    <td>${kurs.ort}</td>
                    <td>${kurs.max_teilnehmeranzahl}</td>
                    <td>${kurs.lehrer_vorname} ${kurs.lehrer_nachname}</td>
                `;
                tbody.appendChild(tr);
            });
        });
});
