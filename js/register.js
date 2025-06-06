document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (event) {
        // Eingabewerte
        const phone = form.phone.value.trim();
        const email = form.email.value.trim();
        const age = parseInt(form.age.value.trim());
        const password = form.password.value.trim();

        // Telefonnummer prüfen (z. B. 10–15 Ziffern, optional + und Leerzeichen)
        const phonePattern = /^\+?[0-9\s\-]{7,15}$/;
        if (!phonePattern.test(phone)) {
            alert("Bitte eine gültige Telefonnummer eingeben (z. B. +43 660 1234567).");
            event.preventDefault();
            return;
        }

        // E-Mail prüfen 
        if (!email.includes("@") || !email.includes(".")) {
            alert("Bitte eine gültige E-Mail-Adresse eingeben.");
            event.preventDefault();
            return;
        }

        // Alter prüfen
        if (isNaN(age) || age < 4 || age > 99) {
            alert("Bitte ein gültiges Alter zwischen 4 und 99 eingeben.");
            event.preventDefault();
            return;
        }

        // Passwort prüfen
        if (password.length < 6) {
            alert("Das Passwort muss mindestens 6 Zeichen lang sein.");
            event.preventDefault();
            return;
        }

        // Erfolgreich
        alert("Formular erfolgreich ausgefüllt – weiter zur Registrierung!");
    });
});
