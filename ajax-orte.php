<?php
// ajax-orte.php
$pdo = new PDO("mysql:host=localhost;dbname=deine_datenbank;charset=utf8", "dein_user", "dein_passwort");

$eingabe = $_GET['q'] ?? '';
if (strlen($eingabe) < 2) exit;

$stmt = $pdo->prepare("SELECT plz, ort FROM orte WHERE ort LIKE :eingabe OR plz LIKE :eingabe LIMIT 10");
$stmt->execute(['eingabe' => $eingabe . '%']);
$ergebnisse = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($ergebnisse as $ort) {
    $anzeige = htmlspecialchars($ort['plz']) . ' ' . htmlspecialchars($ort['ort']);
    echo "<div onclick=\"selectOrt('" . htmlspecialchars($ort['ort']) . "')\">$anzeige</div>";
}
