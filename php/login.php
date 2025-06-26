<?php
session_start();

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = require __DIR__ . "/database.php";

    // Benutzer anhand der E-Mail abrufen
    $sql = "SELECT * FROM nutzer WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    
    if (!$stmt) {
        die("SQL Fehler: " . $mysqli->error);
    }

    $stmt->bind_param("s", $_POST["email"]);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Passwort prüfen
    if ($user && password_verify($_POST["password"], $user["passwort"])) {
        session_regenerate_id();
        $_SESSION["nutzer_id"] = $user["idnutzer"];
        $_SESSION["rolle"] = $user["role"];
        
        // Weiterleitung basierend auf der Rolle des Benutzers
        if ($user["role"] === "admin") {
            header("Location: dashboard_admin.html");
        } elseif ($user["role"] === "reitlehrer") {
            header("Location: dashboard_trainer.html");
        } elseif ($user["role"] === "reitschueler") {
            header("Location: dashboard_schueler.html");
        } else {
            // Unerwartete Rolle:
            echo "Unbekannte Rolle";
        }

        exit;
    }

    $is_invalid = true;
}
?>

