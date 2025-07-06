<?php
header('Content-Type: application/json');
session_start();

if ($_SESSION['role'] !== 'admin') {
    echo json_encode(['status'=>'error','msg'=>'Keine Berechtigung']);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$kursId = (int)($input['kursId'] ?? 0);
if (!$kursId) {
    echo json_encode(['status'=>'error','msg'=>'Ungültige Kurs-ID']);
    exit;
}

$mysqli = new mysqli("localhost","user","passwort","datenbank");
$mysqli->begin_transaction();

try {
    // 1. Anmeldungen entfernen (Fremdschlüssel ON DELETE CASCADE wäre eleganter)
    $del1 = $mysqli->prepare(
        "DELETE FROM reitschueler_termin WHERE termin_idtermin = ?"
    );
    $del1->bind_param("i", $kursId);
    $del1->execute();

    // 2. Bewertungen zum Kurs löschen (falls vorhanden)
    $del2 = $mysqli->prepare(
        "DELETE FROM bewertung WHERE termin_idtermin = ?"
    );
    $del2->bind_param("i", $kursId);
    $del2->execute();

    // 3. Kurs selbst löschen
    $del3 = $mysqli->prepare(
        "DELETE FROM termin WHERE idtermin = ?"
    );
    $del3->bind_param("i", $kursId);
    $del3->execute();

    $mysqli->commit();
    echo json_encode(['status'=>'success','msg'=>'Kurs gelöscht']);

} catch (Throwable $e) {
    $mysqli->rollback();
    echo json_encode(['status'=>'error','msg'=>'Fehler: '.$e->getMessage()]);
}

$mysqli->close();
?>