document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById("personen")) {
        fetch("../php/get_nutzer.php")  // Pfad zur API anpassen
            .then(res => res.json())
            .then(data => {
                const tbody = document.querySelector("#personen tbody");
                tbody.innerHTML = ""; // Leere den Placeholder-Eintrag

                data.forEach(user => {
                    const tr = document.createElement("tr");
                    tr.style.height = "3rem";
                    tr.style.backgroundColor = "#fff7ef";

                    tr.innerHTML = `
                        <td style="padding-right: 3rem;">${user.email.split("@")[0]}</td>
                        <td style="padding-right: 3rem;">${user.email.split("@")[1]}</td>
                        <td style="padding-right: 3rem;">${user.rolle || "–"}</td>
                    `;
                    tbody.appendChild(tr);
                });
            })
            .catch(err => console.error("Fehler beim Laden der Nutzerdaten:", err));
    }
});