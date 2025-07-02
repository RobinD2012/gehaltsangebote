<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Schritt 4 – Erfahrung</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main>
    <h2>Wie viel Berufserfahrung hast du?</h2>
    <form action="step-email.php" method="post">

      <select name="erfahrung" required>
        <option value="">Bitte wählen…</option>
        <option value="keine">Keine</option>
        <option value="unter_1">Unter 1 Jahr</option>
        <option value="1_bis_3">1–3 Jahre</option>
        <option value="mehr_als_3">Mehr als 3 Jahre</option>
      </select>

      <h3>Wie möchtest du arbeiten?</h3>
      <label><input type="checkbox" name="arbeitszeit[]" value="Vollzeit"> Vollzeit</label><br>
      <label><input type="checkbox" name="arbeitszeit[]" value="Teilzeit"> Teilzeit</label><br>
      <label><input type="checkbox" name="arbeitszeit[]" value="Schichtarbeit"> Schichtarbeit</label><br>

      <h3>Was ist dir besonders wichtig?</h3>
      <label><input type="checkbox" name="benefits[]" value="Weiterbildung"> Weiterbildung</label><br>
      <label><input type="checkbox" name="benefits[]" value="Work-Life-Balance"> Work-Life-Balance</label><br>
      <label><input type="checkbox" name="benefits[]" value="Sicherer Arbeitsplatz"> Sicherer Arbeitsplatz</label><br>

      <!-- Hidden Felder -->
      <input type="hidden" name="beruf" value="<?php echo htmlspecialchars($_POST['beruf'] ?? ''); ?>">
      <input type="hidden" name="ort" value="<?php echo htmlspecialchars($_POST['ort'] ?? ''); ?>">
      <input type="hidden" name="radius" value="<?php echo htmlspecialchars($_POST['radius'] ?? '25'); ?>">
      <input type="hidden" name="qualifikation" value="<?php echo htmlspecialchars($_POST['qualifikation'] ?? ''); ?>">
      <input type="hidden" name="abschluss_detail" value="<?php echo htmlspecialchars($_POST['abschluss_detail'] ?? ''); ?>">

      <div class="nav-buttons">
        <a href="step-qualifikation.php" class="back-button">Zurück</a>
        <button type="submit" class="next-button">Weiter</button>
      </div>
    </form>
  </main>
</body>
</html>
