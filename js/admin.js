// admin.js - Admin Dashboard Funktionen

const ROLE = "admin";
const UID = window.USER_ID;

document.addEventListener("DOMContentLoaded", () => {
  ladeBenutzer();
  ladeKurse();
  initKursFormular();
});

// Benutzerverwaltung
function ladeBenutzer() {
  fetch("php/benutzer_liste.php")
    .then(r => r.json())
    .then(benutzer => {
      const tabelle = document.getElementById("benutzerTabelle");
      if (!tabelle) return;
      tabelle.innerHTML = "";
      benutzer.forEach(b => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${b.vorname} ${b.nachname}</td>
          <td>${b.email}</td>
          <td>${b.rolle}</td>
          <td><button onclick="loescheBenutzer(${b.idnutzer})">Löschen</button></td>
        `;
        tabelle.appendChild(tr);
      });
    });
}

function loescheBenutzer(id) {
  fetch("php/benutzer_loeschen.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ idnutzer: id })
  })
    .then(r => r.json())
    .then(d => {
      alert(d.msg);
      ladeBenutzer();
    });
}

// Kursverwaltung
function ladeKurse() {
  fetch("php/termine_sehen.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ rolle: ROLE, nutzerId: UID })
  })
    .then(res => res.json())
    .then(kurse => {
      const tabelle = document.getElementById("kursTabelle");
      if (!tabelle) return;
      tabelle.innerHTML = "";
      kurse.forEach(kurs => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${kurs.kursname}</td>
          <td>${kurs.datum}</td>
          <td>${kurs.uhrzeit}</td>
          <td>${kurs.dauer} min</td>
          <td>${kurs.kosten} €</td>
          <td>${kurs.trainer}</td>
          <td>${kurs.teilnehmer}/${kurs.max_teilnehmer}</td>
          <td>
            <button onclick="openBearbeitenDialog(${kurs.idtermin})">Bearbeiten</button>
            <button onclick="kursLoeschen(${kurs.idtermin})">Löschen</button>
          </td>
        `;
        tabelle.appendChild(tr);
      });
    });
}

function initKursFormular() {
  const form = document.getElementById("kursForm");
  if (!form) return;
  form.classList.remove("hidden");
  form.addEventListener("submit", e => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(form).entries());
    data.nutzerId = UID;
    data.rolle = ROLE;
    fetch("php/kurs_erstellen.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ rolle: ROLE, nutzerId: UID, termin: data })
    })
      .then(r => r.json())
      .then(d => {
        alert(d.msg);
        form.reset();
        ladeKurse();
      });
  });
}

function kursLoeschen(id) {
  fetch("php/kurs_loeschen.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ idtermin: id })
  })
    .then(r => r.json())
    .then(d => {
      alert(d.msg);
      ladeKurse();
    });
}

function openBearbeitenDialog(id) {
  const neuerName = prompt("Neuer Kursname?");
  if (!neuerName) return;
  fetch("php/kurs_bearbeiten.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ idtermin: id, kursname: neuerName })
  })
    .then(r => r.json())
    .then(d => {
      alert(d.msg);
      ladeKurse();
    });
}