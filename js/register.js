HEAD
﻿document.addEventListener("DOMContentLoaded", function () {
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
document.querySelector('form').addEventListener('submit', function (e) {
    e.preventDefault();

    const name = document.getElementById('name').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const email = document.getElementById('email').value.trim();
    const age = parseInt(document.getElementById('age').value, 10);
    const height = parseInt(document.getElementById('height').value, 10);
    const weight = parseInt(document.getElementById('weight').value, 10);
    const level = document.getElementById('level').value;

    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    const passwordError = document.getElementById('passwordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');

    passwordError.innerText = '';
    confirmPasswordError.innerText = '';

    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

    // Passwort leer?
    if (!password) {
        passwordError.innerText = "Bitte gib ein Passwort ein.";
        return;
    }

    // Regeln prüfen
    if (!passwordRegex.test(password)) {
        passwordError.innerText =
            "Mind. 8 Zeichen, Groß-/Kleinschreibung, Zahl und Sonderzeichen nötig.";
        return;
    }

    // Passwort bestätigen prüfen
    if (password !== confirmPassword) {
        confirmPasswordError.innerText = "Passwörter stimmen nicht überein.";
        return;
    }

    if (!name || !phone || !email || !age || !height || !weight || !level) {
        alert("Bitte fülle alle Felder korrekt aus.");
        return;
    }

    const data = {
        name,
        phone,
        email,
        age,
        height,
        weight,
        level,
        password
    };

    fetch('/api/register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                alert("Registrierung erfolgreich!");
                window.location.href = "login.html";
            } else {
                alert("Fehler: " + response.message);
            }
        })
        .catch(err => {
            console.error("Fehler beim Senden:", err);
            alert("Ein unerwarteter Fehler ist aufgetreten.");
        });

});
