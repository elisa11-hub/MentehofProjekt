<?php
header('Content-Type: application/json');
$input = json_decode(file_get_contents("php://input"), true);

$nutzerId = $input['nutzerId'];
$terminId = $input['terminId'];

$mysqli = require __DIR__ . "/database.php";

// Schüler-ID + Level ermitteln
$schuelerQuery = $mysqli->prepare("
    SELECT rs.idreitschueler, ar.reitlevel_idreitlevel
    FROM reitschueler rs
    JOIN aktuelles_reitlevel ar ON rs.idreitschueler = ar.reitschueler_idreitschueler
    WHERE rs.nutzer_idnutzer = ?
");
$schuelerQuery->bind_param("i", $nutzerId);
$schuelerQuery->execute();
$schuelerQuery->bind_result($reitschuelerId, $reitlevelId);
$schuelerQuery->fetch();
$schuelerQuery->close();


// Kursinfos holen
$terminQuery = $mysqli->prepare("
    SELECT min_reitlevel, max_teilnehmerzahl,
           (SELECT COUNT(*) FROM reitschueler_termin WHERE termin_idtermin = ?) as aktuelle_teilnehmer
    FROM termin WHERE idtermin = ?
");
$terminQuery->bind_param("ii", $terminId, $terminId);
$terminQuery->execute();
$terminQuery->bind_result($minLevel, $maxTeilnehmer, $aktuelleTeilnehmer);
$terminQuery->fetch();
$terminQuery->close();

// Prüfen ob schon gebucht
$check = $mysqli->prepare("
    SELECT 1 FROM reitschueler_termin
    WHERE termin_idtermin = ? AND reitschueler_idreitschueler = ?
");
$check->bind_param("ii", $terminId, $reitschuelerId);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
  echo "Du hast diesen Kurs bereits gebucht.";
  exit;
}

// Teilnahmebedingungen prüfen
if ($reitlevelId < $minLevel) {
    echo json_encode(["status" => "error", "msg" => "Reitlevel zu niedrig"]);
    exit;
}

if ($aktuelleTeilnehmer >= $maxTeilnehmer) {
    echo json_encode(["status" => "error", "msg" => "Kurs ist voll"]);
    exit;
}

// Einschreibung
$insert = $mysqli->prepare("INSERT INTO reitschueler_termin (reitschueler_idreitschueler, termin_idtermin, anmeldedatum) VALUES (?, ?, NOW())");
$insert->bind_param("ii", $reitschuelerId, $terminId);
$insert->execute();

echo json_encode(["status" => "success", "msg" => "Erfolgreich eingeschrieben"]);

$mysqli->close();
?>