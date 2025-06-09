<?php
$kursId = $_GET['kurs_id'];
$pdo = new PDO("mysql:host=localhost;dbname=reitschule", "root", "");

$stmt = $pdo->prepare("
  SELECT s.name 
  FROM anmeldungen a
  JOIN schueler s ON a.schueler_id = s.id
  WHERE a.kurs_id = ?
");
$stmt->execute([$kursId]);

$teilnehmer = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo implode("\n", $teilnehmer);
