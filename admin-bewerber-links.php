<?php
require_once 'db_connect.php';

// Alle gespeicherten Bewerber-Links laden (inkl. Bewerberdaten)
$sql = "
    SELECT bl.id, bl.token, b.id AS bewerber_id, b.beruf, b.ort, b.qualifikation, b.erfahrung, b.arbeitszeit, b.benefits
    FROM bewerber_links bl
    JOIN bewerber b ON bl.bewerber_id = b.id
    ORDER BY bl.id DESC
";
$stmt = $pdo->query($sql);
$links = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin – Bewerber-Links</title>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .copy-btn { cursor: pointer; color: blue; text-decoration: underline; }
    </style>
    <script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert("Link kopiert: " + text);
        }, function(err) {
            alert("Fehler beim Kopieren");
        });
    }
    </script>
</head>
<body>

<h2>🔗 Generierte Bewerber-Links</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Beruf</th>
        <th>Ort</th>
        <th>Qualifikation</th>
        <th>Erfahrung</th>
        <th>Arbeitszeit</th>
        <th>Link</th>
    </tr>

    <?php foreach ($links as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['bewerber_id']) ?></td>
            <td><?= htmlspecialchars($row['beruf']) ?></td>
            <td><?= htmlspecialchars($row['ort']) ?></td>
            <td><?= htmlspecialchars($row['qualifikation']) ?></td>
            <td><?= htmlspecialchars($row['erfahrung']) ?></td>
            <td><?= htmlspecialchars($row['arbeitszeit']) ?></td>
            <td>
                <span class="copy-btn" onclick="copyToClipboard('https://meingehaltsvergleich.de/bewerber-einsicht.php?token=<?= $row['token'] ?>')">
                    📋 Link kopieren
                </span>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
