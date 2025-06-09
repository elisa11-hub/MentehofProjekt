document.addEventListener("DOMContentLoaded", () => {
  ladeBenutzer();
  ladeKurse();
});

function ladeBenutzer() {
  fetch("php/benutzer_laden.php")
    .then(res => res.json())
    .then(benutzer => {
      const tabelle = document.getElementById("benutzerTabelle");
      tabelle.innerHTML = "";

      benutzer.forEach(user => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${user.id}</td>
          <td>${user.name}</td>
          <td>${user.rolle}</td>
          <td><button onclick="loescheBenutzer(${user.id})">Löschen</button></td>
        `;
        tabelle.appendChild(tr);
      });
    });
}

function loescheBenutzer(id) {
  if (!confirm("Benutzer wirklich löschen?")) return;

  fetch("php/benutzer_loeschen.php?id=" + id)
    .then(res => res.text())
    .then(msg => {
      alert(msg);
      ladeBenutzer();
    });
}

function ladeKurse() {
  fetch("php/kurse_laden.php")
    .then(res => res.json())
    .then(kurse => {
      const tabelle = document.getElementById("kursTabelle");
      tabelle.innerHTML = "";

      kurse.forEach(kurs => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${kurs.datum}</td>
          <td>${kurs.uhrzeit}</td>
          <td>${kurs.ort}</td>
          <td>${kurs.trainer}</td>
        `;
        tabelle.appendChild(tr);
      });
    });
}
