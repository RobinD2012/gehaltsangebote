<?php
session_start();

require_once 'db_connect.php';

if (!isset($_SESSION['unternehmen_id'])) {
    header("Location: unternehmen-login.php");
    exit();
}

$unternehmen_id = $_SESSION['unternehmen_id'];

// Sicherheitsprüfung: ID vorhanden und numerisch?
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: unternehmen-ausschreibungen.php");
    exit();
}

$id = intval($_GET['id']);

// Nur löschen, wenn die Ausschreibung diesem Unternehmen gehört
$sql = "DELETE FROM ausschreibungen WHERE id = ? AND unternehmen_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $unternehmen_id);
$stmt->execute();

// Zurück zur Übersicht
header("Location: unternehmen-ausschreibungen.php");
exit();
