<?php
require_once 'db_connect.php';

// Token aus der URL
$token = $_GET['token'] ?? '';

// Token prüfen
$stmt = $conn->prepare("SELECT b.* FROM bewerber_links bl JOIN bewerber b ON bl.bewerber_id = b.id WHERE bl.token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Ungültiger oder abgelaufener Link.");
}

$bewerber = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Bewerberprofil</title>
</head>
<body>
    <h1>Details zum Bewerber (anonym)</h1>
    <ul>
        <li><strong>Beruf:</strong> <?= htmlspecialchars($bewerber['beruf']) ?></li>
        <li><strong>Ort:</strong> <?= htmlspecialchars($bewerber['ort']) ?> (±<?= (int)$bewerber['radius'] ?> km)</li>
        <li><strong>Qualifikation:</strong> <?= htmlspecialchars($bewerber['qualifikation']) ?></li>
        <li><strong>Abschluss (Detail):</strong> <?= htmlspecialchars($bewerber['abschluss_detail']) ?></li>
        <li><strong>Erfahrung:</strong> <?= htmlspecialchars($bewerber['erfahrung']) ?></li>
        <li><strong>Arbeitszeit:</strong> <?= htmlspecialchars($bewerber['arbeitszeit']) ?></li>
        <li><strong>Gewünschte Benefits:</strong> <?= htmlspecialchars($bewerber['benefits']) ?></li>
    </ul>

    <hr>

    <h2>Gehaltsangebot einreichen</h2>
    <form action="angebot-einreichen.php" method="post">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <label>Ihr Gehaltsangebot (brutto €/Jahr):<br>
            <input type="number" name="gehalt" required>
        </label><br><br>
        <label>Zusätzliche Leistungen / Kommentar:<br>
            <textarea name="kommentar" rows="4" cols="40"></textarea>
        </label><br><br>
        <button type="submit">Angebot senden</button>
    </form>
</body>
</html>
