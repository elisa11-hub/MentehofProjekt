document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("loginForm");
    const twofaSection = document.getElementById("twofaSection");

    loginForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const email = loginForm.email.value.trim();
        const password = loginForm.password.value.trim();

        // Einfache Prüfung
        if (!email.includes("@") || password.length < 6) {
            alert("Bitte gültige Login-Daten eingeben.");
            return;
        }

        // Simulierter Login → 2FA anzeigen
        alert("Login erfolgreich. 2FA-Code wurde an deine E-Mail gesendet.");
        twofaSection.style.display = "block";
    });
});

function verifyCode() {
    const code = document.getElementById("code").value.trim();
    if (code === "123456") {
        alert("2FA erfolgreich – Weiterleitung...");
        // Hier könnte z. B. redirect kommen
        window.location.href = "dashboard.html";
    } else {
        alert("Falscher Code – bitte erneut versuchen.");
    }
}
