document.addEventListener("DOMContentLoaded", () => {
    fetch("../php/eigene_bewertungen.php")
        .then(response => response.json())
        .then(data => {
            const container = document.querySelector("#bewertungen");
            if (!data.length) {
                container.innerHTML = "<p>Du hast noch keine Bewertungen abgegeben.</p>";
                return;
            }

            data.forEach(b => {
                const div = document.createElement("div");
                div.className = "bewertung";
                div.innerHTML = `
                    <strong>${b.kursname}</strong> (${b.sterne}★) – ${b.lehrer_vorname} ${b.lehrer_nachname}<br>
                    <em>${b.datum}</em><br>
                    <p>${b.kommentar}</p>
                    <hr>
                `;
                container.appendChild(div);
            });
        });
});
