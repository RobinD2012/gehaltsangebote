<?php
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

// Eingaben aus POST
$beruf = $_POST['beruf'] ?? '';
$qualifikation = $_POST['qualifikation'] ?? '';
$erfahrung = $_POST['erfahrung'] ?? '';
$ort = $_POST['ort'] ?? '';
$radius = (int) ($_POST['umkreis'] ?? 25);

// PLZ aus Ort extrahieren
preg_match('/\d{5}/', $ort, $plz_match);
if (!isset($plz_match[0])) {
  die("PLZ nicht erkannt.");
}
$plz = $plz_match[0];

// Koordinaten für Ausgangs-PLZ
$stmt = $pdo->prepare("SELECT lat, lon FROM plz_de WHERE plz = ?");
$stmt->execute([$plz]);
$koord = $stmt->fetch();
if (!$koord) {
  die("PLZ nicht gefunden.");
}
$lat1 = $koord['lat'];
$lon1 = $koord['lon'];

// Umkreis-PLZs berechnen
$stmt = $pdo->prepare("SELECT plz FROM plz_de WHERE (6371 * acos(cos(radians(:lat1)) * cos(radians(lat)) * cos(radians(lon) - radians(:lon1)) + sin(radians(:lat1)) * sin(radians(lat)))) <= :radius");
$stmt->execute([
  ':lat1' => $lat1,
  ':lon1' => $lon1,
  ':radius' => $radius
]);

$plz_array = $stmt->fetchAll(PDO::FETCH_COLUMN);
if (empty($plz_array)) {
  echo "<p>Keine Bewerber im Umkreis gefunden.</p>";
  exit;
}

// SQL für Bewerber-Matching
$in_query = implode(',', array_fill(0, count($plz_array), '?'));
$sql = "SELECT * FROM bewerber WHERE beruf = ? AND qualifikation = ? AND erfahrung = ? AND ort IN ($in_query)";
$params = array_merge([$beruf, $qualifikation, $erfahrung], $plz_array);
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$bewerber = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Suchergebnisse – Bewerber</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main>
    <h1>Suchergebnisse</h1>

    <?php if (count($bewerber) === 0): ?>
      <p>Ihre Suche ergab keine Ergebnisse.<br>
      <em>Lassen Sie sich benachrichtigen, sobald ein passendes Bewerberprofil eingeht.</em></p>
    <?php else: ?>
      <?php foreach ($bewerber as $b): ?>
        <div class="bewerber-card">
          <h3><?php echo htmlspecialchars($b['beruf']); ?></h3>
          <p><strong>Qualifikation:</strong> <?php echo htmlspecialchars($b['qualifikation']); ?></p>
          <p><strong>Erfahrung:</strong> <?php echo htmlspecialchars($b['erfahrung']); ?></p>
          <p><strong>Ort:</strong> <?php echo htmlspecialchars($b['ort']); ?></p>

          <!-- Aktionsbuttons -->
          <form action="unternehmen-bewerber-detail.php" method="post" style="display:inline;">
            <input type="hidden" name="bewerber_id" value="<?php echo $b['id']; ?>">
            <button type="submit">Details ansehen</button>
          </form>

          <form action="unternehmen-angebot-erstellen.php" method="post" style="display:inline;">
            <input type="hidden" name="bewerber_id" value="<?php echo $b['id']; ?>">
            <button type="submit">Angebot abgeben</button>
          </form>

          <form action="unternehmen-merken.php" method="post" style="display:inline;">
            <input type="hidden" name="bewerber_id" value="<?php echo $b['id']; ?>">
            <button type="submit" title="Merken">★</button>
          </form>
        </div>
        <hr>
      <?php endforeach; ?>
    <?php endif; ?>

    <p><a href="unternehmen-suche.php">Zurück zur Suche</a></p>
  </main>
</body>
</html>
