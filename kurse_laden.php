<?php
$pdo = new PDO("mysql:host=localhost;dbname=reitschule", "root", "");
$stmt = $pdo->query("
  X
");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
