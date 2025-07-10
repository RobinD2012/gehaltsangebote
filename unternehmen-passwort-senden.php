<?php
// DB-Verbindung
$pdo = new PDO("mysql:host=db5018128795.hosting-data.io;dbname=dbs14385567;charset=utf8mb4", "dbu3489304", "Apache207!");

$email = $_POST['email'] ?? '';
if (!$email) {
    die("Bitte gib eine E-Mail-Adresse ein.");
}

// Prüfen ob vorhanden
$stmt = $pdo->prepare("SELECT id FROM unternehmen WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    die("E-Mail-Adresse nicht gefunden.");
}

// Token generieren
$token = bin2hex(random_bytes(32));
$expires = date("Y-m-d H:i:s", time() + 3600); // gültig für 1 Stunde

// Token speichern
$stmt = $pdo->prepare("UPDATE unternehmen SET reset_token = ?, reset_expires = ? WHERE id = ?");
$stmt->execute([$token, $expires, $user['id']]);

// E-Mail senden (vereinfachtes Beispiel)
$resetLink = "https://deine-domain.de/unternehmen-passwort-neu.php?token=" . urlencode($token);
$betreff = "Passwort zurücksetzen";
$nachricht = "Hallo,\n\nKlicke auf den folgenden Link, um dein Passwort zurückzusetzen:\n\n$resetLink\n\nDieser Link ist 1 Stunde gültig.";
$headers = "From: support@meingehaltsvergleich.de";

mail($email, $betreff, $nachricht, $headers);

echo "Ein Link zum Zurücksetzen des Passworts wurde gesendet.";
?>
