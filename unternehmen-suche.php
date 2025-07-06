<?php
header('Content-Type: text/html; charset=utf-8');

$host = "db5018128795.hosting-data.io";
$dbname = "dbs14385567";
$username = "dbu3489304";
$password = "Apache207!";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
} catch (PDOException $e) {
    die("DB-Verbindung fehlgeschlagen");
}

// Bei AJAX-Anfrage für Berufe
if (isset($_GET['ajax']) && $_GET['ajax'] === 'beruf' && isset($_GET['query'])) {
    $query = $_GET['query'];
    if (strlen($query) < 2) exit;
    $stmt = $pdo->prepare("SELECT name FROM berufe WHERE name LIKE :query LIMIT 10");
    $stmt->execute(['query' => $query . '%']);
    echo json_encode($stmt->fetchAll(PDO::FETCH_COLUMN));
    exit;
}

// Bei AJAX-Anfrage für Orte
if (isset($_GET['ajax']) && $_GET['ajax'] === 'ort' && isset($_GET['query'])) {
    $query = $_GET['query'];
    if (strlen($query) < 2) exit;
    $stmt = $pdo->prepare("SELECT CONCAT(plz, ' ', ort) FROM plz_de WHERE ort LIKE :query OR plz LIKE :query GROUP BY plz, ort ORDER BY ort ASC LIMIT 10");
    $stmt->execute(['query' => $query . '%']);
    echo json_encode($stmt->fetchAll(PDO::FETCH_COLUMN));
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Bewerber finden</title>
  <link rel="stylesheet" href="style.css">
  <style>
    #beruf-list, #ort-list {
      border: 1px solid #ccc;
      max-height: 150px;
      overflow-y: auto;
      position: absolute;
      background: white;
      z-index: 1000;
      width: 100%;
    }
    .typeahead-item {
      padding: 5px;
      cursor: pointer;
    }
    .typeahead-item:hover {
      background-color: #eee;
    }
  </style>
</head>
<body>
  <nav>
    <ul>
      <li><a href="unternehmen-dashboard.html">Dashboard</a></li>
      <li><a href="unternehmen-bewerber.html">Bewerberanfragen</a></li>
      <li><a href="unternehmen-suche.php" class="active">Neue Suche</a></li>
      <li><a href="unternehmen-ausschreibungen.html">Meine Ausschreibungen</a></li>
      <li><a href="unternehmen-profil.html">Profil</a></li>
    </ul>
  </nav>

  <main>
    <h1>Passende Bewerber finden</h1>
    <form action="suche-bewerber.php" method="POST" autocomplete="off">
      
      <!-- Beruf -->
      <label for="beruf">Beruf</label>
      <input type="text" id="beruf" name="beruf" placeholder="z. B. Bauleiter" required>
      <div id="beruf-list"></div>

      <!-- Qualifikation -->
      <label for="qualifikation">Qualifikation</label>
      <select id="qualifikation" name="qualifikation">
        <option value="">Beliebig</option>
        <option value="ausbildung">Ausbildung</option>
        <option value="bachelor">Bachelor</option>
        <option value="master">Master</option>
        <option value="promotion">Promotion</option>
      </select>

      <!-- Berufserfahrung -->
      <label for="erfahrung">Berufserfahrung</label>
      <select id="erfahrung" name="erfahrung">
        <option value="">Beliebig</option>
        <option value="0-2">0–2 Jahre</option>
        <option value="3-5">3–5 Jahre</option>
        <option value="6-10">6–10 Jahre</option>
        <option value="10plus">mehr als 10 Jahre</option>
      </select>

      <!-- Ort -->
      <label for="ort">Arbeitsort (PLZ oder Stadt)</label>
      <input type="text" id="ort" name="ort" required>
      <div id="ort-list"></div>

      <!-- Umkreis -->
      <label for="umkreis">Umkreis (km)</label>
      <input type="number" id="umkreis" name="umkreis" value="25" min="5" max="100">

      <!-- Arbeitszeit -->
      <label>Arbeitszeit</label>
      <select name="arbeitszeit">
        <option value="">Beliebig</option>
        <option value="vollzeit">Vollzeit</option>
        <option value="teilzeit">Teilzeit</option>
      </select>

      <button type="submit">Bewerber anzeigen</button>
    </form>
  </main>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    function setupTypeahead(inputId, listId, type) {
      const input = document.getElementById(inputId);
      const list = document.getElementById(listId);

      input.addEventListener('input', function() {
        const value = input.value;
        if (value.length < 2) {
          list.innerHTML = '';
          return;
        }

        fetch(`unternehmen-suche.php?ajax=${type}&query=` + encodeURIComponent(value))
          .then(response => response.json())
          .then(data => {
            list.innerHTML = '';
            data.forEach(item => {
              const div = document.createElement('div');
              div.textContent = item;
              div.classList.add('typeahead-item');
              div.addEventListener('click', () => {
                input.value = item;
                list.innerHTML = '';
              });
              list.appendChild(div);
            });
          });
      });
    }

    setupTypeahead('beruf', 'beruf-list', 'beruf');
    setupTypeahead('ort', 'ort-list', 'ort');
  });
  </script>
</body>
</html>
