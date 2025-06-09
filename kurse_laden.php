<?php
$pdo = new PDO("mysql:host=localhost;dbname=reitschule", "root", "");
$stmt = $pdo->query("
  SELECT k.datum, k.uhrzeit, k.ort, b.name AS trainer
  FROM kurse k
  JOIN benutzer b ON k.trainer_id = b.id
");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
