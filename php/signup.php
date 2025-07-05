<?php

if (empty($_POST["Vorname"])) {
    die("Firstname is required");
}

if (empty($_POST["Nachname"])) {
    die("Lastname is required");
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
    die("Password must contain at least on special character");
}


if ($_POST["Passwort"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}




$mysqli = require __DIR__ . "/database.php";

// Passwort sicher hashen
$password_hash = password_hash($_POST["passwort"], PASSWORD_DEFAULT);

// Formulardaten erfassen (mit Fallback für leere Werte)
$vorname = $_POST["vorname"] ?? null;
$nachname = $_POST["nachname"] ?? null;
$geburtsdatum = $_POST["geburtsdatum"] ?? null;
$email = $_POST["email"] ?? null;
$telefon = $_POST["telefonnummer"] ?? null;
$gewicht = $_POST["koerpergewicht"] ?? null;
$groesse = $_POST["koerpergroesse"] ?? null;
$reitlevel = $_POST["level"] ?? null;

// === Schritt 1: Nutzer einfügen ===
$sql = "INSERT INTO Nutzer (email, passwort, rolle) VALUES (?, ?, 'Reitschüler')";
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
$sql = "INSERT INTO Reitschueler (idreitschueler, vorname, nachname, geburtsdatum, telefonnummer, koerpergewicht, koerpergroesse)
               VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);
if (!$stmt) die("SQL error (Nutzer): " . $mysqli->error);
$stmt->bind_param("issssdd", $nutzer_id,$vorname, $nachname, $geburtsdatum, $telefonnummer, $koerpergewicht, $koerpergroesse);
$stmt->execute();

// === Schritt 3: Aktuelles Reitlevel zuweisen ===
$sql = "INSERT INTO Aktuelles_Reitlevel (idreitlevel, idschueler) VALUES (?, ?)";
$stmt = $mysqli->prepare($sql);
if (!$stmt) die("SQL error (Reitlevel): " . $mysqli->error);
$stmt->bind_param("ii", $reitlevel_id, $nutzer_id);
$stmt->execute();

// Weiterleitung zur Login-Seite
 header("Location: login.php");
exit;

?>