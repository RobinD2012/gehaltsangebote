<?php
$host = 'db5018128795.hosting-data.io';
$db   = 'dbs14385567';
$user = 'dbu3489304';
$pass = 'Apache207!';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
  die('Verbindungsfehler: ' . $e->getMessage());
}
