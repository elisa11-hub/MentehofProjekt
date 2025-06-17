<?php
session_start();

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = require __DIR__ . "/database.php";

    // Benutzer anhand der E-Mail abrufen
    $sql = "SELECT * FROM Authentifizierung WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    
    if (!$stmt) {
        die("SQL Fehler: " . $mysqli->error);
    }

    $stmt->bind_param("s", $_POST["email"]);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Passwort prüfen
    if ($user && password_verify($_POST["password"], $user["password_hash"])) {
        session_regenerate_id();
        $_SESSION["user_id"] = $user["idAuthentifizierung"];
        $_SESSION["nutzer_id"] = $user["Nutzer_idNutzer"];
        header("Location: ?? ");
        exit;
    }

    $is_invalid = true;
}
?>

