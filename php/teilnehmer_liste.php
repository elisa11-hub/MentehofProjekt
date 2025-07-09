<?php

$mysqli = require __DIR__ . "/database.php";

$kidtermin = $_GET['idtermin'] ?? null;
if (!$idtermin) {
    die("No course id given");
}

// SQL: Teilnehmer (Schülername + Telefonnummer) für diesen Kurs abfragen
$sql = "
    SELECT
        r.vorname,
        r.nachname,
        r.telefonnummer
    FROM Reitschueler_Termin rt
    JOIN Reitschueler r ON rt.reitschueler_idreitschueler = r.idreitschueler
    WHERE rt.termin_idtermin = ?
";

$stmt = $mysqli->prepare($sql);
if (!$stmt) die("SQL Fehler: " . $mysqli->error);

$stmt->bind_param("i", $kidtermin);
$stmt->execute();

$result = $stmt->get_result();

$teilnehmerListe = [];
while ($row = $result->fetch_assoc()) {
    $teilnehmerListe[] = $row['vorname'] . " " . $row['nachname'] . " (" . $row['telefonnummer'] . ")";
}

echo implode("\n", $teilnehmerListe);
?>
