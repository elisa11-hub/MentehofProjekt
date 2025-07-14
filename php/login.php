<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = require __DIR__ . "/../database/database.php";

    $email = $_POST["email"] ?? '';
    $password = $_POST["passwort"] ?? '';

    // Nutzer suchen (Achtung: keine Prepared Statements -> nur lokal verwenden!)
    $stmt = $mysqli->prepare("SELECT * FROM nutzer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["passwort"])) {
        $_SESSION["nutzer_id"] = $user["idnutzer"];
        $_SESSION["rolle"] = $user["rolle"];
        header("Location: ../html/dashboard.html");
        exit;
    }

    header("Location: ../html/login.html?fehler=1");
    exit();
}






