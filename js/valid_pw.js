const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('fehler') === '1') {
        const fehlermeldung = document.getElementById("fehlermeldung");
        fehlermeldung.textContent = "Benutzername oder Passwort ist falsch.";
    }