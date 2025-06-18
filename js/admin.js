/* ------------------------------------------------------------------ */
/* Globale Konstanten (per PHP-Template o. Ä. ins <head> schreiben)   */
/* ------------------------------------------------------------------ */
// z. B.  <script>window.USER_ROLE="schueler";window.USER_ID=7;</script>
const ROLE = window.USER_ROLE;     // "schueler" | "reitlehrer" | "admin"
const UID  = window.USER_ID;       // numerische Nutzer-ID (primary key)

/* ------------------------------------------------------------------ */
/* DOM READY                                                          */
/* ------------------------------------------------------------------ */
document.addEventListener("DOMContentLoaded", () => {
  ladeBenutzer();  // unverändert
  ladeKurse();     // wurde erweitert
  if (ROLE === "reitlehrer" || ROLE === "admin") {
    initKursFormular(); // Formular zum Kurs-Erstellen
  }
});

/* ------------------------------------------------------------------ */
/* 1) BENUTZER – Laden / Löschen  (unverändert)                       */
/* ------------------------------------------------------------------ */
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
          <td>
            <button onclick="loescheBenutzer(${user.id})">Löschen</button>
          </td>
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

/* ------------------------------------------------------------------ */
/* 2) KURSE – Laden + rollenabhängige Aktionen                        */
/* ------------------------------------------------------------------ */
function ladeKurse() {
  // Rolle & Nutzer-ID an das Backend senden, damit es gefiltert zurück­liefert
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

/* ------------------------------------------------------------------ */
/* 3) Aktions-Buttons je nach Rolle                                   */
/* ------------------------------------------------------------------ */
function baueAktionsButtons(kurs) {
  const td = document.getElementById(`aktion_${kurs.idtermin}`);

  /* ---- Schüler: Einschreiben / Austragen ---- */
  if (ROLE === "schueler") {
    if (kurs.angemeldet) {
      const btn = mkButton("Austragen", () => austragen(kurs.idtermin));
      td.appendChild(btn);
    } else if (kurs.teilnehmer < kurs.max_teilnehmer && kurs.level_ok) {
      const btn = mkButton("Einschreiben", () => einschreiben(kurs.idtermin));
      td.appendChild(btn);
    } else {
      td.textContent = "keine Plätze";
    }
    return;
  }

  /* ---- Lehrer: nur eigene Kurse bearbeiten ---- */
  if (ROLE === "reitlehrer" && kurs.lehrer_id === UID) {
    td.appendChild(mkButton("Bearbeiten", () => openBearbeitenDialog(kurs)));
  }

  /* ---- Admin: alle Kurse bearbeiten/löschen ---- */
  if (ROLE === "admin") {
    td.appendChild(mkButton("Bearbeiten", () => openBearbeitenDialog(kurs)));
    td.appendChild(mkButton("Löschen", () => kursLoeschen(kurs.idtermin)));
  }
}

/* ------------------------------------------------------------------ */
/* 4) Schüler-Aktionen                                                */
/* ------------------------------------------------------------------ */
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


/* ------------------------------------------------------------------ */
/* 5) Lehrer & Admin – Kurs anlegen                                   */
/* ------------------------------------------------------------------ */
function initKursFormular() {
  const form = document.getElementById("kursForm");
  form.classList.remove("hidden");                   // Formular einblenden

  form.addEventListener("submit", e => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(form).entries());
    data.nutzerId = UID;
    data.rolle    = ROLE;

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

/* ------------------------------------------------------------------ */
/* 6) Kurs bearbeiten & löschen                                       */
/* ------------------------------------------------------------------ */
function openBearbeitenDialog(kurs) {
  // Modal oder Prompt – hier minimalistisches Prompt-Beispiel
  const neuerName = prompt("Neuer Kursname:", kurs.kursname);
  if (neuerName === null) return; // abgebrochen

  fetch("php/kurs_bearbeiten.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      rolle: ROLE,
      kursId: kurs.idtermin,
      daten: { kursname: neuerName }
    })
  })
    .then(r => r.json())
    .then(d => {
      alert(d.msg);
      ladeKurse();
    });
}

function kursLoeschen(id) {
  if (!confirm("Kurs wirklich löschen?")) return;
  fetch("php/kurs_loeschen.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ rolle: ROLE, kursId: id })
  })
    .then(r => r.json())
    .then(d => {
      alert(d.msg);
      ladeKurse();
    });
}

/* ------------------------------------------------------------------ */
/* 7) Helfer-Funktion                                                 */
/* ------------------------------------------------------------------ */
function mkButton(label, handler) {
  const btn = document.createElement("button");
  btn.textContent = label;
  btn.addEventListener("click", handler);
  return btn;
}
