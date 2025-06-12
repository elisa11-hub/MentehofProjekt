<?php

$mysqli = require __DIR__ . "/database.php";
$kursId = $_GET['kurs_id'];


$stmt = $pdo->prepare("
X
");
$stmt->execute([$kursId]);

$teilnehmer = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo implode("\n", $teilnehmer);
