<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Schritt 2 – Arbeitsort</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main>
    <h2>Wo suchst du nach einem Job?</h2>
    <form action="step-qualifikation.php" method="post">
      <input type="text" name="ort" placeholder="z. B. Berlin, München…" required>
      <label for="radius">Umkreis in km:</label>
      <select id="radius" name="radius">
        <option value="10">10 km</option>
        <option value="25" selected>25 km</option>
        <option value="50">50 km</option>
        <option value="100">100 km</option>
      </select>

      <!-- Hidden Beruf -->
      <input type="hidden" name="beruf" value="<?php echo htmlspecialchars($_POST['beruf'] ?? ''); ?>">

      <div class="nav-buttons">
        <a href="step-beruf.php" class="back-button">Zurück</a>
        <button type="submit" class="next-button">Weiter</button>
      </div>
    </form>
  </main>
</body>
</html>
