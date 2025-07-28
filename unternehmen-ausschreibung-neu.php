<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['unternehmen_id'])) {
    header("Location: unternehmen-login.php");
    exit;
}

$unternehmen_id = $_SESSION['unternehmen_id'];
$errors = [];
$success = false;

// Berufe laden
$berufe = [];
$result = $conn->query("SELECT bezeichnung FROM berufe ORDER BY bezeichnung ASC");
while ($row = $result->fetch_assoc()) {
    $berufe[] = $row['bezeichnung'];
}

// Formular absenden
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $beruf = $_POST['beruf'] ?? '';
    $ort = $_POST['ort'] ?? '';
    $umkreis = intval($_POST['umkreis'] ?? 0);
    $qualifikationen = $_POST['qualifikationen'] ?? [];
    $erfahrung = $_POST['erfahrung'] ?? '';
    $arbeitszeit = $_POST['arbeitszeit'] ?? '';
    $benefits = $_POST['benefits'] ?? '';

    // Validierung
    if (!in_array($beruf, $berufe)) {
        $errors[] = "Der ausgewählte Beruf ist ungültig.";
    }
    if (empty($ort)) {
        $errors[] = "Bitte geben Sie einen Ort ein.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO ausschreibungen 
                (unternehmen_id, beruf, ort, umkreis, qualifikationen, erfahrung, arbeitszeit, benefits, erstellt_am) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $qualifikationen_str = implode(',', $qualifikationen);
        $stmt->bind_param("ississss", $unternehmen_id, $beruf, $ort, $umkreis, $qualifikationen_str, $erfahrung, $arbeitszeit, $benefits);
        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $success = true;
            header("Location: unternehmen-ausschreibungen.php");
            exit;
        } else {
            $errors[] = "Beim Speichern ist ein Fehler aufgetreten.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Neue Ausschreibung</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f9f9f9;
            padding: 30px;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.05);
        }
        label {
            display: block;
            margin-top: 18px;
            font-weight: bold;
        }
        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="checkbox"] {
            margin-right: 6px;
        }
        .checkbox-group {
            margin-top: 10px;
        }
        button {
            margin-top: 25px;
            background: #3498db;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .error {
            background: #ffdddd;
            color: #c0392b;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Neue Ausschreibung anlegen</h1>

    <?php if ($errors): ?>
        <div class="error">
            <?php foreach ($errors as $e): ?>
                <p><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label for="beruf">Beruf:</label>
        <select name="beruf" id="beruf" required>
            <option value="">Bitte wählen</option>
            <?php foreach ($berufe as $b): ?>
                <option value="<?= htmlspecialchars($b) ?>" <?= ($beruf ?? '') === $b ? 'selected' : '' ?>>
                    <?= htmlspecialchars($b) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="ort">Ort:</label>
        <input type="text" name="ort" id="ort" value="<?= htmlspecialchars($ort ?? '') ?>" required>

        <label for="umkreis">Umkreis (km):</label>
        <select name="umkreis" id="umkreis">
            <?php foreach ([5, 10, 20, 50, 100] as $val): ?>
                <option value="<?= $val ?>" <?= ($umkreis ?? '') == $val ? 'selected' : '' ?>>
                    <?= $val ?> km
                </option>
            <?php endforeach; ?>
        </select>

        <label>Qualifikationen:</label>
        <div class="checkbox-group">
            <?php foreach (['ausbildung', 'meister', 'techniker', 'studium'] as $q): ?>
                <label><input type="checkbox" name="qualifikationen[]" value="<?= $q ?>"
                    <?= isset($qualifikationen) && in_array($q, $qualifikationen) ? 'checked' : '' ?>> <?= ucfirst($q) ?></label><br>
            <?php endforeach; ?>
        </div>

        <label for="erfahrung">Erfahrung:</label>
        <select name="erfahrung" id="erfahrung">
            <option value="">Bitte wählen</option>
            <?php foreach (['keine', '0–2 Jahre', '2–5 Jahre', 'mehr als 5 Jahre'] as $e): ?>
                <option value="<?= $e ?>" <?= ($erfahrung ?? '') === $e ? 'selected' : '' ?>>
                    <?= $e ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="arbeitszeit">Arbeitszeit:</label>
        <select name="arbeitszeit" id="arbeitszeit">
            <option value="">Bitte wählen</option>
            <option value="Vollzeit" <?= ($arbeitszeit ?? '') === 'Vollzeit' ? 'selected' : '' ?>>Vollzeit</option>
            <option value="Teilzeit" <?= ($arbeitszeit ?? '') === 'Teilzeit' ? 'selected' : '' ?>>Teilzeit</option>
        </select>

        <label for="benefits">Benefits (optional):</label>
        <textarea name="benefits" id="benefits" rows="3"><?= htmlspecialchars($benefits ?? '') ?></textarea>

        <button type="submit">Ausschreibung speichern</button>
    </form>
</body>
</html>
