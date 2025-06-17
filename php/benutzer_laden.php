<?php
$mysqli = require __DIR__ . "/database.php";

$stmt = $pdo->query("SELECT X");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
