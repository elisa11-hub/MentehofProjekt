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
