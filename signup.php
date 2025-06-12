<?php

if (empty($_POST["Vorname"])) {
    die("Vorname is required");
}

if (empty($_POST["Nachname"])) {
    die("Nachname is required");
}

if (strlen($_POST["Passwort"]) < 8) {
    die("Password must be at least 8 characters");
}

if ( ! preg_match("/[a-z]/i", $_POST["Passwort"])) {
    die("Password must contain at least one letter");
}

if ( ! preg_match("/[0-9]/", $_POST["Passwort"])) {
    die("Password must contain at least one number");
}


if (!preg_match("/[!@#$%^&*(),.?\":{}|<>_\-+=~`]/", $_POST["Passwort"])) {
    die("Das Passwort muss mindestens ein Sonderzeichen enthalten.");
}


if ($_POST["Passwort"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}




$mysqli = require __DIR__ . "/database.php";

// Passwort sicher hashen
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

// Formulardaten erfassen (mit Fallback für leere Werte)
$vorname = $_POST["vorname"] ?? null;
$nachname = $_POST["nachname"] ?? null;
$geburtsdatum = $_POST["geburtsdatum"] ?? null;
$email = $_POST["email"] ?? null;
$telefon = $_POST["phone"] ?? null;
$alter = $_POST["age"] ?? null;
$gewicht = $_POST["weight"] ?? null;
$groesse = $_POST["height"] ?? null;
$reitlevel = $_POST["level"] ?? null;

// === Schritt 1: Person einfügen ===
$sql_person = "INSERT INTO Person (Vorname, Nachname, Telefonnummer) VALUES (?, ?, ?)";
$stmt = $mysqli->prepare($sql_person);
if (!$stmt) die("SQL error (Person): " . $mysqli->error);
$stmt->bind_param("sss", $vorname, $nachname, $telefon);
$stmt->execute();
$person_id = $stmt->insert_id;

// === Schritt 2: Nutzer einfügen ===
$sql_nutzer = "INSERT INTO Nutzer (Geburtsdatum, Alter, Gewicht, Groesse, Reitlevel, Person_idPerson)
               VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql_nutzer);
if (!$stmt) die("SQL error (Nutzer): " . $mysqli->error);
$stmt->bind_param("siiddi", $geburtsdatum, $alter, $gewicht, $groesse, $reitlevel, $person_id);
$stmt->execute();
$nutzer_id = $stmt->insert_id;

// === Schritt 3: Authentifizierung einfügen ===
$sql_auth = "INSERT INTO Authentifizierung (email, password_hash, Nutzer_idNutzer)
             VALUES (?, ?, ?)";
$stmt = $mysqli->prepare($sql_auth);
if (!$stmt) die("SQL error (Auth): " . $mysqli->error);
$stmt->bind_param("ssi", $email, $password_hash, $nutzer_id);

// Ausführen und Ergebnis prüfen
if ($stmt->execute()) {
    header("Location: login.php");
    exit;
} else {
    if ($mysqli->errno === 1062) {
        die("Fehler: Diese E-Mail ist bereits registriert.");
    } else {
        die("SQL-Fehler bei Authentifizierung: " . $mysqli->error);
    }
}

?>







