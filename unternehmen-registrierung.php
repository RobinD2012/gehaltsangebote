<?php
session_start();
$fehler = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $pdo = new PDO("mysql:host=db5018128795.hosting-data.io;dbname=dbs14385567;charset=utf8mb4", "dbu3489304", "Apache207!");
  } catch (PDOException $e) {
    $fehler = "Fehler bei der Verbindung zur Datenbank.";
  }

  $firma = trim($_POST['firma'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $passwort = $_POST['password'] ?? '';

  if ($firma && $email && $passwort) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM unternehmen WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
      $fehler = "Diese E-Mail ist bereits registriert.";
    } else {
      $hash = password_hash($passwort, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("INSERT INTO unternehmen (firma, email, passwort_hash) VALUES (?, ?, ?)");
      $stmt->execute([$firma, $email, $hash]);
      $_SESSION['unternehmen_email'] = $email;
      header("Location: unternehmen-dashboard.php");
      exit;
    }
  } else {
    $fehler = "Bitte fülle alle Felder aus.";
  }
}
?>

<!-- HTML-Teil unten (wie bei dir) -->
