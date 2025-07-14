<?php
$pdo = new PDO("mysql:host=localhost;dbname=mydb2", "root", "");
$sql = "SELECT * FROM termin";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$kurse = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($kurse);
?>

