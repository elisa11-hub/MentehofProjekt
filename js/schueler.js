const ROLE = "schueler";
const UID = window.USER_ID;

document.addEventListener("DOMContentLoaded", () => {
  ladeKurse();
});

function ladeKurse() {
  fetch("php/termine_sehen.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ rolle: ROLE, nutzerId: UID })
  })
    .then(res => res.json())
    .then(kurse => {
      const tabelle = document.getElementById("kursTabelle");
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
          <td id="aktion_${kurs.idtermin}"></td>
        `;
        tabelle.appendChild(tr);
        baueAktionsButtons(kurs);
      });
    });
}

function baueAktionsButtons(kurs) {
  const td = document.getElementById(`aktion_${kurs.idtermin}`);
  if (kurs.angemeldet) {
    td.appendChild(mkButton("Austragen", () => austragen(kurs.idtermin)));
  } else if (kurs.teilnehmer < kurs.max_teilnehmer && kurs.level_ok) {
    td.appendChild(mkButton("Einschreiben", () => einschreiben(kurs.idtermin)));
  } else {
    td.textContent = "keine Plätze";
  }
}

function einschreiben(terminId) {
  fetch("php/kurs_einschreiben.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ nutzerId: UID, terminId })
  })
    .then(r => r.json())
    .then(d => {
      alert(d.msg);
      ladeKurse();
    });
}

function austragen(terminId) {
  fetch("php/kurs_austragen.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ nutzerId: UID, terminId })
  })
    .then(r => r.json())
    .then(d => {
      alert(d.msg);
      ladeKurse();
    });
}

function mkButton(label, handler) {
  const btn = document.createElement("button");
  btn.textContent = label;
  btn.addEventListener("click", handler);
  return btn;
}
