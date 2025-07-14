<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Nicht eingeloggt"]);
    exit;
}

$mysqli = require __DIR__ . "/../database/database.php";

// Hole Reitlehrer-ID
$user_id = $_SESSION["user_id"];
$stmt = $mysqli->prepare("SELECT idreitlehrer FROM reitlehrer WHERE nutzer_idnutzer = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$reitlehrer_id = $row["idreitlehrer"] ?? null;

if (!$reitlehrer_id) {
    http_response_code(403);
    echo json_encode(["error" => "Kein Reitlehrer gefunden"]);
    exit;
}

// Formulardaten lesen
$data = json_decode(file_get_contents("php://input"), true);

// Daten validieren (rudimentär)
$required = ["kursname", "bezeichnung", "datum", "uhrzeit"];
foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(["error" => "Feld '$field' fehlt"]);
        exit;
    }
}

// SQL einfügen
$stmt = $mysqli->prepare("
    INSERT X
");

$stmt->bind_param(
    "sssssdiiii",
    $data["kursname"],
    $data["bezeichnung"],
    $data["datum"],
    $data["uhrzeit"],
    $data["ort"],
    $data["kosten"],
    $data["dauer"],
    $data["max_teilnehmeranzahl"],
    $data["min_reitlevel"],
    $reitlehrer_id
);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Fehler beim Speichern"]);
}
