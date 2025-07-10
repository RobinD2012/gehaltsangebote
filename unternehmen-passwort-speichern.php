<?php
// DB-Verbindung
$pdo = new PDO("mysql:host=db5018128795.hosting-data.io;dbname=dbs14385567;charset=utf8mb4", "dbu3489304", "Apache207!");

$token = $_POST['token'] ?? '';
$pass1 = $_POST['pass1'] ?? '';
$pass2 = $_POST['pass2'] ?? '';

// Passwort prüfen
if (!$pass1 || !$pass2 || $pass1 !== $pass2) {
    die("Bitte gib zwei identische Passwörter ein.");
}

// Passwortregeln
if (strlen($pass1) < 8 || !preg_match('/[A-Z]/', $pass1) || !preg_match('/[a-z]/', $pass1) || !preg_match('/[\W]/', $pass1)) {
    die("Passwort muss mindestens 8 Zeichen lang sein, Groß- und Kleinbuchstaben sowie ein Sonderzeichen enthalten.");
}

// Token prüfen
$stmt = $pdo->prepare("SELECT id, reset_expires FROM unternehmen WHERE reset_token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user || strtotime($user['reset_expires']) < time()) {
    die("Der Link ist ungültig oder abgelaufen.");
}

// Passwort speichern
$hash = password_hash($pass1, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE unternehmen SET passwort = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
$stmt->execute([$hash, $user['id']]);

echo "Passwort erfolgreich gespeichert. Du kannst dich jetzt <a href='unternehmen-login.php'>einloggen</a>.";
?>
