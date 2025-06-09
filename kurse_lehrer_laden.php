<?php
$pdo = new PDO("mysql:host=localhost;dbname=reitschule", "root", "");

// Beispielhaft: nur Kurse für Frau Gruber (LehrerID = 1)
$stmt = $pdo->prepare("
  SELECT k.id, k.datum, k.uhrzeit, k.ort, 
         (SELECT COUNT(*) FROM anmeldungen a WHERE a.kurs_id = k.id) AS teilnehmeranzahl
  FROM kurse k
  WHERE k.trainer_id = 1
  ORDER BY k.datum
");
$stmt->execute();

$kurse = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($kurse);
