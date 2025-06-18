<?php
header('Content-Type: application/json');
$input = json_decode(file_get_contents("php://input"), true);
$rolle = $input['rolle'] ?? '';
$nutzerId = $input['nutzerId'] ?? 0;

$mysqli = require __DIR__ . "/database.php";

// Basis-SQL, wird unten je nach Rolle erweitert
$sql = "
X
";

// Rollenbasierte Einschränkungen
if ($rolle === 'schueler') {
    $sql .= "X
    )"; //nur zu passendem reitlevel
} elseif ($rolle === 'admin' || $rolle === 'reitlehrer' ) {
    // Keine WHERE-Klausel – Admin&Lehrer sieht alles
} else {
    echo json_encode([]); // Unbekannte Rolle
    exit;
}

$sql .= " GROUP BY X";

$stmt = $mysqli->prepare($sql);
if ($rolle === 'admin') {
    $stmt->execute();
} else {
    $stmt->bind_param("i", $nutzerId);
    $stmt->execute();
}
$result = $stmt->get_result();

$termine = [];
while ($row = $result->fetch_assoc()) {
    $termine[] = $row;
}

echo json_encode($termine);
$mysqli->close();
