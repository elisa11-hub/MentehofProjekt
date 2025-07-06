<?php
$mysqli = require __DIR__ . "/database.php";

// Prüfen ob ID übergeben wurde
if (!isset($_GET['id'])) {
    exit("Ungültige Anfrage: ID fehlt.");
}

$id = (int)$_GET['id'];

// Sicherstellen, dass Benutzer kein Admin ist
$stmt = $mysqli->prepare("SELECT role FROM nutzer WHERE idnutzer = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    exit("Benutzer nicht gefunden.");
}

$user = $result->fetch_assoc();
if ($user['role'] === 'admin') {
    exit("Admin kann nicht gelöscht werden.");
}

// Löschen
$stmt = $mysqli->prepare("DELETE FROM nutzer WHERE idnutzer = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Benutzer erfolgreich gelöscht.";
} else {
    echo "Fehler beim Löschen.";
}
?>
