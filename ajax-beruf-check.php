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
    echo json_encode(['valid' => false]);
    exit;
}

// Beruf aus Anfrage
$beruf = $_GET['beruf'] ?? '';
$beruf = trim($beruf);

// Prüfung, ob der Beruf in der Tabelle vorhanden ist
$stmt = $pdo->prepare("SELECT COUNT(*) FROM berufe WHERE bezeichnung = :beruf");
$stmt->execute(['beruf' => $beruf]);
$exists = $stmt->fetchColumn();

echo json_encode(['valid' => $exists > 0]);
