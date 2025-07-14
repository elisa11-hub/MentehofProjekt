<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['nutzerId'])) {
    echo json_encode(["error" => "Nicht eingeloggt"]);
    exit;
}

echo json_encode([
    "nutzerId" => $_SESSION['nutzerId'],
    "rolle" => $_SESSION['rolle']
]);
