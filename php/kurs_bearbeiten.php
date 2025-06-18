<?php
header('Content-Type: application/json');
session_start();

if ($_SESSION['role'] !== 'admin') {
    echo json_encode(['status'=>'error','msg'=>'Keine Berechtigung']);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$kursId = (int)$input['kursId'];
$daten  = $input['daten'] ?? [];

if (!$kursId || empty($daten)) {
    echo json_encode(['status'=>'error','msg'=>'Ungültige Parameter']);
    exit;
}

$set = [];
$params = [];
$types  = '';

$felder = [
    'kursname'           => 's',
    'bezeichnung'        => 's',
    'datum'              => 's',
    'uhrzeit'            => 's',
    'dauer'              => 'i',
    'kosten'             => 'd',
    'max_teilnehmerzahl' => 'i',
    'min_reitlevel'      => 'i',
    'reitlehrer_idreitlehrer' => 'i'   // Admin darf ggf. Lehrer wechseln
];

foreach ($felder as $feld => $typ) {
    if (isset($daten[$feld])) {
        $set[]   = "$feld = ?";
        $params[]= $daten[$feld];
        $types  .= $typ;
    }
}

if (!$set) {
    echo json_encode(['status'=>'error','msg'=>'Nichts zu ändern']);
    exit;
}

$mysqli = new mysqli("localhost","user","passwort","datenbank");
$sql = "UPDATE termin SET ".implode(', ', $set)." WHERE idtermin = ?";
$types .= 'i';
$params[] = $kursId;

$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();

echo json_encode(['status'=>'success','msg'=>'Kurs aktualisiert']);
