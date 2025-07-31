<?php
require_once 'db_connect.php';

// Token aus URL holen und prüfen
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Ungültiger Zugriff.");
}

$token = $_GET['token'];

// Token prüfen und zugehörigen Bewerber finden
$stmt = $pdo->prepare("SELECT b.* FROM bewerber_links l JOIN bewerber b ON l.bewerber_id = b.id WHERE l.token = ?");
$stmt->execute([$token]);
$bewerber = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bewerber) {
    die("Link ungültig oder abgelaufen.");
}

// Wenn das Formular abgesendet wurde:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gehalt = $_POST['gehaltsangebot'] ?? null;
    $benefits = $_POST['benefits'] ?? '';
    $firma = $_POST['unternehmensname'] ?? '';

    if ($gehalt && $firma) {
        $stmt = $pdo->prepare("INSERT INTO unternehmen_angebote (token, gehaltsangebot, benefits, unternehmensname) VALUES (?, ?, ?, ?)");
        $stmt->execute([$token, $gehalt, $benefits, $firma]);
        $erfolg = true;
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Bewerber-Einsicht</title>
    <style>
        body { font-family: sans-serif; max-width: 700px; margin: auto; padding: 20px; }
        .card { border: 1px solid #ccc; padding: 20px; margin-bottom: 30px; border-radius: 5px; }
        label { font-weight: bold; display: block; margin-top: 15px; }
        input[type="text"], input[type="number"], textarea { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 20px; padding: 10px 20px; background: #0077cc; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .success { background: #d4edda; padding: 10px; border: 1px solid #c3e6cb; margin-bottom: 20px; color: #155724; }
    </style>
</head>
<body>

    <h2>Bewerberprofil</h2>

    <?php if (!empty($erfolg)): ?>
        <div class="success">Vielen Dank! Ihr Angebot wurde erfolgreich übermittelt.</div>
    <?php endif; ?>

    <div class="card">
        <p><strong>Beruf:</strong> <?= htmlspecialchars($bewerber['beruf']) ?></p>
        <p><strong>Ort:</strong> <?= htmlspecialchars($bewerber['ort']) ?></p>
        <p><strong>Qualifikation:</strong> <?= htmlspecialchars($bewerber['qualifikation']) ?></p>
        <p><strong>Erfahrung:</strong> <?= htmlspecialchars($bewerber['erfahrung']) ?></p>
        <p><strong>Arbeitszeit:</strong> <?= htmlspecialchars($bewerber['arbeitszeit']) ?></p>
        <p><strong>Gewünschte Benefits:</strong> <?= htmlspecialchars($bewerber['benefits']) ?></p>
    </div>

    <h3>Ihr Gehaltsangebot</h3>
    <form method="POST">
        <label for="unternehmensname">Ihr Unternehmensname *</label>
        <input type="text" name="unternehmensname" id="unternehmensname" required>

        <label for="gehaltsangebot">Monatliches Bruttogehalt in € *</label>
        <input type="number" name="gehaltsangebot" id="gehaltsangebot" step="0.01" required>

        <label for="benefits">Weitere freiwillige Leistungen (Benefits)</label>
        <textarea name="benefits" id="benefits" rows="4" placeholder="z. B. Firmenwagen, Homeoffice, Weiterbildung …"></textarea>

        <button type="submit">Angebot absenden</button>
    </form>

</body>
</html>
