<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['unternehmen_id'])) {
    header("Location: unternehmen-login.php");
    exit();
}

$unternehmen_id = $_SESSION['unternehmen_id'];

// ID aus der URL lesen
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: unternehmen-ausschreibungen.php");
    exit();
}

$id = intval($_GET['id']);

// Verarbeitung nach Formular-Absenden
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $beruf = trim($_POST['beruf']);
    $ort = trim($_POST['ort']);
    $qualifikation = $_POST['qualifikation'];
    $erfahrung = $_POST['erfahrung'];
    $arbeitszeit = $_POST['arbeitszeit'];

    // Einfaches Update (ohne Validierung)
    $sql_update = "UPDATE ausschreibungen 
                   SET beruf = ?, ort = ?, qualifikation = ?, erfahrung = ?, arbeitszeit = ?
                   WHERE id = ? AND unternehmen_id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssssiii", $beruf, $ort, $qualifikation, $erfahrung, $arbeitszeit, $id, $unternehmen_id);
    $stmt->execute();

    header("Location: unternehmen-ausschreibungen.php");
    exit();
}

// Bestehende Ausschreibung laden
$sql = "SELECT * FROM ausschreibungen WHERE id = ? AND unternehmen_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $unternehmen_id);
$stmt->execute();
$result = $stmt->get_result();
$ausschreibung = $result->fetch_assoc();

if (!$ausschreibung) {
    echo "Ausschreibung nicht gefunden oder unberechtigt.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Ausschreibung bearbeiten</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background: #3498db;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <h1>Ausschreibung bearbeiten</h1>

    <form method="post">
        <label>Beruf:</label>
        <input type="text" name="beruf" required value="<?= htmlspecialchars($ausschreibung['beruf']) ?>">

        <label>Ort:</label>
        <input type="text" name="ort" required value="<?= htmlspecialchars($ausschreibung['ort']) ?>">

        <label>Qualifikation:</label>
        <select name="qualifikation" required>
            <option value="ausbildung" <?= $ausschreibung['qualifikation'] == 'ausbildung' ? 'selected' : '' ?>>Ausbildung</option>
            <option value="meister" <?= $ausschreibung['qualifikation'] == 'meister' ? 'selected' : '' ?>>Meister</option>
            <option value="techniker" <?= $ausschreibung['qualifikation'] == 'techniker' ? 'selected' : '' ?>>Techniker</option>
            <option value="studium" <?= $ausschreibung['qualifikation'] == 'studium' ? 'selected' : '' ?>>Studium</option>
        </select>

        <label>Erfahrung:</label>
        <select name="erfahrung" required>
            <option value="0–2 Jahre" <?= $ausschreibung['erfahrung'] == '0–2 Jahre' ? 'selected' : '' ?>>0–2 Jahre</option>
            <option value="3–5 Jahre" <?= $ausschreibung['erfahrung'] == '3–5 Jahre' ? 'selected' : '' ?>>3–5 Jahre</option>
            <option value="mehr als 5 Jahre" <?= $ausschreibung['erfahrung'] == 'mehr als 5 Jahre' ? 'selected' : '' ?>>mehr als 5 Jahre</option>
        </select>

        <label>Arbeitszeit:</label>
        <select name="arbeitszeit" required>
            <option value="vollzeit" <?= $ausschreibung['arbeitszeit'] == 'vollzeit' ? 'selected' : '' ?>>Vollzeit</option>
            <option value="teilzeit" <?= $ausschreibung['arbeitszeit'] == 'teilzeit' ? 'selected' : '' ?>>Teilzeit</option>
        </select>

        <button type="submit">Änderungen speichern</button>
    </form>
</body>
</html>
