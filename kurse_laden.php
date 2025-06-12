<?php

$mysqli = require __DIR__ . "/database.php";

$stmt = $pdo->query("
  X
");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
