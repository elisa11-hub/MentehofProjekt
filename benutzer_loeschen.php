<?php
if (!isset($_GET['id'])) exit("Ungültige Anfrage");


$stmt = $pdo->prepare("DELETE X");
$stmt->execute([$_GET['id']]);
echo "Benutzer gelöscht.";
