const ROLE = "reitlehrer";
const UID = window.USER_ID;

document.addEventListener("DOMContentLoaded", () => {
  ladeKurse();
  initKursFormular();
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
          <td>${kurs.teilnehmer}/${kurs.max_teilnehmer}</td>
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
