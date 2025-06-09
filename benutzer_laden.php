<?php
$pdo = new PDO("mysql:host=localhost;dbname=reitschule", "root", "");
$stmt = $pdo->query("SELECT id, name, rolle FROM benutzer ORDER BY name");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
