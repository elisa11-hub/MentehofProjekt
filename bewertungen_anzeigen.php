<?php
session_start();
$mysqli = require __DIR__ . "/database.php";

$nutzer_id = $_SESSION["nutzer_id"] ?? null;
if (!$nutzer_id) {
    http_response_code(401);
    echo json_encode(["error" => "Nicht eingeloggt"]);
    exit;
}

// Rolle abrufen
$sql = "SELECT rolle FROM nutzer WHERE idnutzer = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $nutzer_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode([]);
    exit;
}

$rolle = $user["rolle"];

// Bewertungen abhängig von Rolle
switch ($rolle) {
    case "admin":
        $sql = "SELECT * FROM bewertung";
        $stmt = $mysqli->prepare($sql);
        break;

    case "lehrer":
        $sql = "SELECT b.* FROM bewertung b
                JOIN reitlehrer r ON b.reitlehrer_idreitlehrer = r.idreitlehrer
                WHERE r.nutzer_idnutzer = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $nutzer_id);
        break;

    case "schueler":
    default:
        echo json_encode([]);
        exit;
}

$stmt->execute();
$result = $stmt->get_result();

$bewertungen = [];
while ($row = $result->fetch_assoc()) {
    $bewertungen[] = [
        "reitschueler_id" => $row["reitschueler_idreitschueler"],
        "reitlehrer_id" => $row["reitlehrer_idreitlehrer"],
        "termin_id" => $row["termin_idtermin"],
        "sterne" => $row["sterne"],
        "kommentar" => $row["kommentar"],
        "datum" => $row["datum"]
    ];
}

header("Content-Type: application/json");
echo json_encode($bewertungen);
