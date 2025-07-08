<?php

header('Content-Type: application/json');
$input = json_decode(file_get_contents("php://input"), true);
$rolle = $input['rolle'] ?? '';
$nutzerId = $input['nutzerId'] ?? 0;

$mysqli = require __DIR__ . "/database.php";

$sql = "
SELECT
        t.idtermin,
        t.kursname,
        t.bezeichnung,
        t.datum,
        t.uhrzeit,
        t.dauer,
        t.max_teilnehmerzahl,
        t.reitlehrer_idreitlehrer,
        (SELECT COUNT(*) FROM reitschueler_termin rt WHERE rt.termin_idtermin = t.idtermin) AS teilnehmer_anzahl
    FROM termin t
";

// Rollenbasierte Einschränkungen
// WHERE-Klausel
$where = "";
$params = [];
$param_types = "";

if ($rolle === 'admin') {
    // Sieht alle Termine, keine Einschränkung
} elseif ($rolle === 'reitlehrer' ) {
    // Sieht nur Termine, bei denen er selbst der Reitlehrer ist
    $where = " WHERE t.reitlehrer_idreitlehrer = ?";
    $params[] = $nutzerId;
    $param_types .= "i";
} elseif ($rolle === 'schueler') {
    // Sieht nur Termine, bei denen er angemeldet ist
    $sql .= "
        JOIN reitschueler r ON r.nutzer_idnutzer = ?
        JOIN reitschueler_termin rt ON rt.reitschueler_idreitschueler = r.idreitschueler
    ";
    $where = " WHERE rt.termin_idtermin = t.idtermin";
    $params[] = $nutzerId;
    $param_types .= "i";
} else {
    // Unbekannte Rolle
    echo json_encode([]); 
    exit;
}

$sql .= $where;
$sql .= " ORDER BY t.datum, t.uhrzeit";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    die("SQL Fehler: " . $mysqli->error);
}

// Parameter binden, falls vorhanden
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}

$stmt->execute();

$result = $stmt->get_result();

$termine = [];
while ($row = $result->fetch_assoc()) {
    // Startzeit im ISO 8601 Format (für Kalender-Plugins wie FullCalendar)
    $start_datetime = $row['datum'] . 'T' . substr($row['uhrzeit'], 0, 5); // "YYYY-MM-DDTHH:MM"

    // Endzeit berechnen (Dauer in Minuten)
    $start_ts = strtotime($row['datum'] . ' ' . $row['uhrzeit']);
    $end_ts = $start_ts + ($row['dauer'] * 60);
    $end_datetime = date('Y-m-d\TH:i', $end_ts);

$termine[] = [
        'id' => $row['idtermin'],
        'title' => $row['kursname'],
        'beschreibung' => $row['bezeichnung'],
        'start' => $start_datetime,
        'end' => $end_datetime,
        'dauer' => (int)$row['dauer'],
        'max_teilnehmerzahl' => (int)$row['max_teilnehmerzahl'],
        'teilnehmer_anzahl' => (int)$row['teilnehmer_anzahl'],
        'reitlehrer_id' => $row['reitlehrer_idreitlehrer'],
    ];
}

echo json_encode($termine);
$mysqli->close();

?>