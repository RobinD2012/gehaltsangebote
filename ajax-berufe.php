<?php
header('Content-Type: application/json');

// DB-Verbindung
$host = "db5018128795.hosting-data.io";
$dbname = "dbs14385567";
$username = "dbu3489304";
$password = "Apache207!";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
} catch (PDOException $e) {
    echo json_encode([]);
    exit;
}

// Eingabe holen und vorbereiten
$query = $_GET['q'] ?? '';
$query = trim($query);

// Auch Umlaut-Ersatz ermöglichen (ä = ae, ö = oe etc.)
$ersatz = ['ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss',
           'Ä' => 'Ae', 'Ö' => 'Oe', 'Ü' => 'Ue'];
$queryNorm = strtr($query, $ersatz);

// SQL: Vorschläge anhand bezeichnung
$sql = "
  SELECT bezeichnung 
  FROM berufe 
  WHERE bezeichnung LIKE :such1 
     OR bezeichnung LIKE :such2
  ORDER BY bezeichnung ASC
  LIMIT 10
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'such1' => $query . '%',
    'such2' => $queryNorm . '%'
]);

$berufe = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo json_encode($berufe);
