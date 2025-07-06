<?php
$mysqli = require __DIR__ . "/database.php";

$query = "
SELECT
    t.name,
    n.name AS trainer,
    t.datum,
    t.uhrzeit AS zeit,
FROM termin t
JOIN nutzer n ON t.reitlehrer_idreitlehrer = n.idnutzer
ORDER BY t.datum, t.uhrzeit  
";

$result = $conn->query($query);

echo "<table border='1'><tr><th>Kurs</th><th>Trainer</th><th>Datum</th><th>Pferd</th><th>Zeit</th><th>Status</th></tr>";
while ($row = $result->fetch_assoc()) {
  echo "<tr>
    <td>{$row['name']}</td>
    <td>{$row['trainer']}</td>
    <td>{$row['datum']}</td>
    <td>{$row['pferd']}</td>
    <td>{$row['zeit']}</td>
    <td>{$row['status']}</td>
  </tr>";
}
echo "</table>";
?>
