<?php
if (!isset($_GET['id'])) exit("Ungültige Anfrage");
$pdo = new PDO("mysql:host=localhost;dbname=reitschule", "root", "");
$stmt = $pdo->prepare("DELETE FROM benutzer WHERE id = ?");
$stmt->execute([$_GET['id']]);
echo "Benutzer gelöscht.";
