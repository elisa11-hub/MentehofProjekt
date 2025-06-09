<?php
$conn = new mysqli("localhost", "root", "", "reitdatenbank");

$schueler_id = 1; // Beispiel

$query = "
SELECT k.name, k.trainer, k.datum, b.pferd, b.zeit, b.status 
FROM buchungen b
JOIN kurse k ON b.kurs_id = k.id
WHERE b.schueler_id = $schueler_id
ORDER BY k.datum
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
