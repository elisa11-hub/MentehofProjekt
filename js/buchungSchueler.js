function ladeKurse() {
  fetch("kurse_laden.php")
    .then(response => response.text())
    .then(html => {
      document.getElementById("kursliste").innerHTML = html;
    });
}

function bucheKurs(kursId) {
  const formData = new FormData();
  formData.append("kurs_id", kursId);
  formData.append("schueler_id", 1); 
  formData.append("pferd", "Bella"); 
  formData.append("zeit", "10:00");

  fetch("kurs_buchen.php", {
    method: "POST",
    body: formData
  })
    .then(response => response.text())
    .then(text => {
      alert(text);
      ladeKurse(); // Kursliste aktualisieren
    });
}

// Automatisch Kurse laden beim Seitenstart
document.addEventListener("DOMContentLoaded", () => {
  ladeKurse();
});
