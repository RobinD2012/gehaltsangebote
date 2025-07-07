<?php
$host = "db5018128795.hosting-data.io";
$dbname = "dbs14385567";
$username = "dbu3489304";
$password = "Apache207!";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
} catch (PDOException $e) {
    die("Verbindung fehlgeschlagen: " . $e->getMessage());
}

// Formulardaten abholen
$beruf = $_POST['beruf'] ?? '';
$qualifikation = $_POST['qualifikation'] ?? '';
$erfahrung = $_POST['erfahrung'] ?? '';
$ort_input = $_POST['ort'] ?? '';
$umkreis = (int)($_POST['umkreis'] ?? 25);
$arbeitszeit = $_POST['arbeitszeit'] ?? '';

// PLZ extrahieren (aus "50667 Köln" → "50667")
preg_match('/\d{5}/', $ort_input, $plz_match);
$plz = $plz_match[0] ?? '';

if (!$plz) {
    die("Ungültige PLZ.");
}

// Koordinaten der Start-PLZ holen
$stmt = $pdo->prepare("SELECT lat, lon FROM plz_de WHERE plz = :plz LIMIT 1");
$stmt->execute(['plz' => $plz]);
$koordinaten = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$koordinaten) {
    die("PLZ nicht gefunden.");
}

$lat1 = $koordinaten['lat'];
$lon1 = $koordinaten['lon'];

// PLZs im Umkreis ermitteln
$stmt = $pdo->prepare("
    SELECT plz FROM plz_de
    WHERE (
        6371 * acos(
            cos(radians(:lat1)) * cos(radians(lat)) *
            cos(radians(lon) - radians(:lon1)) +
            sin(radians(:lat1)) * sin(radians(lat))
        )
    ) <= :radius
");
$stmt->execute([
    'lat1' => $lat1,
    'lon1' => $lon1,
    'radius' => $umkreis
]);

$plz_liste = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (empty($plz_liste)) {
    die("Keine passenden Orte im Umkreis gefunden.");
}

// Bewerber abrufen
$sql = "SELECT * FROM bewerber WHERE beruf = :beruf";
$params = ['beruf' => $beruf];

// Optional: weitere Filter
if ($qualifikation !== '') {
    $sql .= " AND qualifikation = :qualifikation";
    $params['qualifikation'] = $qualifikation;
}

if ($erfahrung !== '') {
    $sql .= " AND berufserfahrung = :erfahrung";
    $params['erfahrung'] = $erfahrung;
}

if ($arbeitszeit !== '') {
    $sql .= " AND arbeitszeit = :arbeitszeit";
    $params['arbeitszeit'] = $arbeitszeit;
}

if (!empty($plz_liste)) {
    // Platzhalter für IN()
    $in = str_repeat('?,', count($plz_liste) - 1) . '?';
    $sql .= " AND plz IN ($in)";
    $params = array_merge($params, $plz_liste);
}

$sql .= " ORDER BY timestamp DESC LIMIT 50";
$stmt = $pdo->prepare($sql);
$stmt->execute(array_values($params));
$bewerber = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Suchergebnisse</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <nav>
    <ul>
      <li><a href="unternehmen-dashboard.html">Dashboard</a></li>
      <li><a href="unternehmen-bewerber.html" class="active">Bewerberanfragen</a></li>
      <li><a href="unternehmen-suche.php">Neue Suche</a></li>
      <li><a href="unternehmen-ausschreibungen.html">Meine Ausschreibungen</a></li>
      <li><a href="unternehmen-profil.html">Profil</a></li>
    </ul>
  </nav>

  <main>
    <h1>Gefundene Bewerber</h1>

    <?php if (count($bewerber) > 0): ?>
      <ul>
        <?php foreach ($bewerber as $b): ?>
          <li>
            <strong>Beruf:</strong> <?= htmlspecialchars($b['beruf']) ?><br>
            <strong>Qualifikation:</strong> <?= htmlspecialchars($b['qualifikation']) ?><br>
            <strong>Erfahrung:</strong> <?= htmlspecialchars($b['berufserfahrung']) ?><br>
            <strong>Ort (PLZ):</strong> <?= htmlspecialchars($b['plz']) ?><br>
            <strong>Arbeitszeit:</strong> <?= htmlspecialchars($b['arbeitszeit']) ?><br>
            <strong>Letzte Aktivität:</strong> <?= date("d.m.Y H:i", strtotime($b['timestamp'])) ?><br>
            <button>Angebot abgeben</button>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>Keine passenden Bewerber gefunden.</p>
    <?php endif; ?>
  </main>
</body>
</html>
