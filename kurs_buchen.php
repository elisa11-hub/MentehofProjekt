<?php


$kurs_id = $_POST['kurs_id'];
$schueler_id = $_POST['schueler_id'];
$pferd = $_POST['pferd'];
$zeit = $_POST['zeit'];

// Prüfen ob schon gebucht
$check = $conn->prepare("X");
$check->bind_param("ii", $kurs_id, $schueler_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
  echo "Du hast diesen Kurs bereits gebucht.";
  exit;
}

// Buchung einfügen
$stmt = $conn->prepare("INSERT X");
$stmt->bind_param("iiss", $schueler_id, $kurs_id, $pferd, $zeit);
$stmt->execute();

echo "Buchung erfolgreich!";
?>
