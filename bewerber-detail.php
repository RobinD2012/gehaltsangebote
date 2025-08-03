<?php
// Datenbankverbindung
$host = "db5018128795.hosting-data.io";
$dbname = "dbs14385567";
$username = "dbu3489304";
$password = "Apache207!";
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// Token prüfen
$token = $_GET['token'] ?? '';
if (empty($token)) {
    die("Kein gültiger Zugriff.");
}

// Bewerber anhand Token holen
$sql = "
    SELECT b.*
    FROM bewerber_links bl
    JOIN bewerber b ON bl.bewerber_id = b.id
    WHERE bl.token = ?
    LIMIT 1
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$bewerber = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$bewerber) {
    die("Bewerber nicht gefunden.");
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Bewerberdetails</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f6f9;
      padding: 2em;
    }
    .detail-container {
      max-width: 700px;
      margin: auto;
      background-color: #fff;
      padding: 2em;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    .detail-container h2 {
      color: #0077cc;
      margin-bottom: 20px;
    }
    .detail-item {
      margin-bottom: 15px;
    }
    .detail-label {
      font-weight: bold;
      color: #555;
    }
    .angebot-button {
      display: inline-block;
      margin-top: 30px;
      padding: 12px 24px;
      background-color: #0077cc;
      color: white;
      border-radius: 8px;
      text-decoration: none;
      font-size: 16px;
    }
    .angebot-button:hover {
      background-color: #005fa3;
    }
  </style>
</head>
<body>

<div class="detail-container">
  <h2>Bewerberprofil</h2>

  <div class="detail-item"><span class="detail-label">Beruf:</span> <?= htmlspecialchars($bewerber['beruf']) ?></div>
  <div class="detail-item"><span class="detail-label">Ort:</span> <?= htmlspecialchars($bewerber['ort']) ?> (Umkreis: <?= htmlspecialchars($bewerber['radius']) ?> km)</div>
  <div class="detail-item"><span class="detail-label">Qualifikation:</span> <?= htmlspecialchars($bewerber['qualifikation']) ?></div>
  <?php if (!empty($bewerber['abschluss_detail'])): ?>
  <div class="detail-item"><span class="detail-label">Abschluss-Details:</span> <?= htmlspecialchars($bewerber['abschluss_detail']) ?></div>
  <?php endif; ?>
  <div class="detail-item"><span class="detail-label">Berufserfahrung:</span> <?= htmlspecialchars($bewerber['erfahrung']) ?></div>
  <div class="detail-item"><span class="detail-label">Arbeitszeit:</span> <?= htmlspecialchars($bewerber['arbeitszeit']) ?></div>
  <?php if (!empty($bewerber['benefits'])): ?>
  <div class="detail-item"><span class="detail-label">Gewünschte Benefits:</span> <?= htmlspecialchars($bewerber['benefits']) ?></div>
  <?php endif; ?>
  <div class="detail-item"><span class="detail-label">Eingereicht am:</span> <?= date("d.m.Y H:i", strtotime($bewerber['eingereicht_am'])) ?></div>

  <a class="angebot-button" href="angebot-einreichen.php?token=<?= urlencode($token) ?>">Jetzt Gehaltsangebot einreichen</a>
</div>

</body>
</html>
