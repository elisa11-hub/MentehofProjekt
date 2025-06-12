<?php
$kursId = $_GET['kurs_id'];
$pdo = new PDO("mysql:host=localhost;dbname=reitschule", "root", "");

$stmt = $pdo->prepare("
X
");
$stmt->execute([$kursId]);

$teilnehmer = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo implode("\n", $teilnehmer);
