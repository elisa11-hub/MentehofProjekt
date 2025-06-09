function ladeKurse() {
  fetch("kurse_lehrer_laden.php")
    .then(response => response.json())
    .then(kurse => {
      const tbody = document.getElementById("kursTabelle");
      tbody.innerHTML = "";

      kurse.forEach(kurs => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${kurs.datum}</td>
          <td>${kurs.uhrzeit}</td>
          <td>${kurs.ort}</td>
          <td>${kurs.teilnehmeranzahl}</td>
          <td><a href="#" onclick="zeigeTeilnehmer(${kurs.id}); return false;">Anzeigen</a></td>
        `;
        tbody.appendChild(tr);
      });
    });
}

function zeigeTeilnehmer(kursId) {
  fetch("teilnehmer_liste.php?kurs_id=" + kursId)
    .then(response => response.text())
    .then(html => {
      alert("Teilnehmer:innen für Kurs " + kursId + ":\n\n" + html);
    });
}

// Beim Laden
document.addEventListener("DOMContentLoaded", ladeKurse);
