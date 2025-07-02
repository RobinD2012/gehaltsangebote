<?php
// Datenbankverbindung
$host = "db5018128795.hosting-data.io";
$user = "dbu3489304";
$pass = "Bautechniker21!";
$dbname = "dbs14385567";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

$berufe = [];
$sql = "SELECT name FROM berufe ORDER BY name ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $berufe[] = $row["name"];
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Schritt 1 – Beruf auswählen</title>
  <link rel="stylesheet" href="style.css">
  <style>
    main {
      max-width: 600px;
      margin: 2em auto;
      padding: 2em;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
    select, button {
      width: 100%;
      padding: 0.8em;
      font-size: 1em;
      margin-top: 1em;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    .nav-buttons {
      margin-top: 2em;
      display: flex;
      justify-content: space-between;
    }
    .next-button {
      background-color: #0074D9;
      color: white;
      border: none;
      font-weight: bold;
      cursor: pointer;
    }
    .back-button {
      text-decoration: none;
      color: #0074D9;
      font-weight: bold;
      padding: 0.8em;
    }
  </style>
</head>
<body>
  <main>
    <h2>Welchen Beruf übst du aus?</h2>
    <form action="step-ort.html" method="get">
      <label for="beruf">Bitte wähle deinen Beruf:</label>
      <select id="beruf" name="beruf" required>
        <option value="">Bitte auswählen</option>
        <?php foreach ($berufe as $beruf): ?>
          <option value="<?= htmlspecialchars($beruf) ?>"><?= htmlspecialchars($beruf) ?></option>
        <?php endforeach; ?>
      </select>
      <div class="nav-buttons">
        <a href="index.html" class="back-button">Zurück</a>
        <button type="submit" class="next-button">Weiter</button>
      </div>
    </form>
  </main>
</body>
</html>
