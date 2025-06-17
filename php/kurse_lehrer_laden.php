<?php
$mysqli = require __DIR__ . "/database.php";

$stmt = $pdo->prepare("
  X
");
$stmt->execute();

$kurse = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($kurse);
