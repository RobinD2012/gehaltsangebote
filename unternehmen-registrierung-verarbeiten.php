<?php
session_start();

// DB-Verbindung
$host = "db5018128795.hosting-data.io";
$dbname = "dbs14385567";
$username = "dbu3489304";
$password = "Apache207!";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
} catch (PDOException $e) {
    die("Verbindung fehlgeschlagen: " . $e->getMessage());
}

// Formulardaten holen
$firma = $_POST['firma'] ?? '';
$email = $_POST['email'] ?? '';
$passwort = $_POST['password'] ?? '';

// Felder prüfen
if (empty($firma) || empty($email) || empty($passwort)) {
    die("Bitte fülle alle Felder aus.");
}

// E-Mail prüfen
$stmt = $pdo->prepare("SELECT COUNT(*) FROM unternehmen WHERE email = :email");
$stmt->execute(['email' => $email]);
if ($stmt->fetchColumn() > 0) {
    die("Diese E-Mail-Adresse ist bereits registriert.");
}

// Passwort hashen
$hash = password_hash($passwort, PASSWORD_DEFAULT);

// Einfügen
$stmt = $pdo->prepare("INSERT INTO unternehmen (firma, email, passwort) VALUES (:firma, :email, :passwort)");
$erfolg = $stmt->execute([
    'firma' => $firma,
    'email' => $email,
    'passwort' => $hash
]);

if ($erfolg) {
    $_SESSION['unternehmen_email'] = $email;
    header("Location: unternehmen-dashboard.php");
    exit;
} else {
    die("Registrierung fehlgeschlagen. Bitte versuche es später erneut.");
}
?>
