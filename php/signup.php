<?php

if (empty($_POST["vorname"])) {
    die("Firstname is required");
}

if (empty($_POST["nachname"])) {
    die("Lastname is required");
}

if (strlen($_POST["passwort"]) < 8) {
    die("Password must be at least 8 characters");
}

if ( ! preg_match("/[a-z]/i", $_POST["passwort"])) {
    die("Password must contain at least one letter");
}

if ( ! preg_match("/[0-9]/", $_POST["passwort"])) {
    die("Password must contain at least one number");
}


if (!preg_match("/[!@#$%^&*(),.?\":{}|<>_\-+=~`]/", $_POST["passwort"])) {
    die("Password must contain at least on special character");
}


if ($_POST["passwort"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}


$mysqli = require __DIR__ . "/../database/database.php";

// Passwort sicher hashen
$password_hash = password_hash($_POST["passwort"], PASSWORD_DEFAULT);

// Formulardaten erfassen (mit Fallback für leere Werte)
$vorname = $_POST["vorname"] ?? null;
$nachname = $_POST["nachname"] ?? null;
$geburtsdatum = $_POST["geburtsdatum"] ?? null;
$alter = $_POST["alter"] ?? null;
$email = $_POST["email"] ?? null;
$telefon = $_POST["telefonnummer"] ?? null;
$gewicht = $_POST["gewicht"] ?? null;
$groesse = $_POST["groesse"] ?? null;
$reitlevel = $_POST["level"] ?? null;

// === Schritt 1: Nutzer einfügen ===
$sql = "INSERT INTO nutzer (email, passwort, rolle) VALUES (?, ?, 'Reitschüler')";
$stmt = $mysqli->prepare($sql);
if (!$stmt) die("SQL error (Nutzer): " . $mysqli->error);
$stmt->bind_param("ss", $email, $password_hash);
if (!$stmt->execute()){
    if ($mysqli->errno === 1062) {
        die("Fehler: Diese E-Mail ist bereits registriert.");
    } else {
        die("SQL-Fehler bei Authentifizierung: " . $mysqli->error);
    }
}
$nutzer_id = $stmt->insert_id;

// === Schritt 2: Reitschüler einfügen ===
$sql = "INSERT INTO reitschueler (idreitschueler, vorname, nachname, geburtsdatum, alter, telefonnummer, gewicht, groesse)
               VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);
if (!$stmt) die("SQL error (Nutzer): " . $mysqli->error);
$stmt->bind_param("isssssdd", $nutzer_id,$vorname, $nachname, $geburtsdatum, $alter, $telefonnummer, $koerpergewicht, $koerpergroesse);
$stmt->execute();

// === Schritt 3: Aktuelles Reitlevel zuweisen ===
$sql = "INSERT INTO aktuelles_reitlevel (reitlevel_idreitlevel, reitschueler_idreitschueler) VALUES (?, ?)";
$stmt = $mysqli->prepare($sql);
if (!$stmt) die("SQL error (Reitlevel): " . $mysqli->error);
$stmt->bind_param("ii", $reitlevel_id, $nutzer_id);
$stmt->execute();

// Weiterleitung zur Login-Seite
 header("Location: login.php");
exit;

?>