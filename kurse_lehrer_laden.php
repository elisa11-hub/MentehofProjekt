<?php
$pdo = new PDO("mysql:host=localhost;dbname=reitschule", "root", "");

// Beispielhaft: nur Kurse für Frau Gruber (LehrerID = 1)
$stmt = $pdo->prepare("
  X
");
$stmt->execute();

$kurse = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($kurse);
