<?php
$pdo = new PDO("mysql:host=localhost;dbname=mydb2", "root", "");
$sql = "SELECT email, rolle FROM nutzer";  
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($users);
?>
