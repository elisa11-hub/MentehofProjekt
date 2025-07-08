<?php
$mysqli = require __DIR__ . "/database.php";

// nur Schüler und Reitlehrer laden, nicht Admin (gibt es nur einen)
$sql = "
SELECT 
    CONCAT(nachname, ', ', vorname) AS name,
    email,
    role
FROM nutzer
WHERE role IN ('reitlehrer', 'reitschueler')
ORDER BY nachname ASC
";

$result = $mysqli->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => $mysqli->error]);
    exit;
}

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

header('Content-Type: application/json');
echo json_encode($users);
?>