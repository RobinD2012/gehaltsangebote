<?php
$host = "db5018128795.hosting-data.io";
$dbname = "dbs14385567";
$username = "dbu3489304";
$password = "Apache207!";

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
} catch (PDOException $e) {
    echo json_encode(['valid' => false]);
    exit;
}

$ort = $_GET['ort'] ?? '';
if (!preg_match('/^\d{5}\s+[A-ZÄÖÜßA-Z\s\-]+$/u', $ort)) {
    echo json_encode(['valid' => false]);
    exit;
}

list($plz, $ortname) = explode(' ', $ort, 2);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM plz_de WHERE plz = :plz AND ort = :ort");
$stmt->execute([
    'plz' => $plz,
    'ort' => $ortname
]);

echo json_encode(['valid' => $stmt->fetchColumn() > 0]);
