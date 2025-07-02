<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Schritt – Arbeitszeit</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main>
    <h2>Wie möchtest du arbeiten?</h2>
    <form action="step-benefits.php" method="post">

      <label><input type="checkbox" name="arbeitszeit[]" value="Vollzeit"> Vollzeit</label><br>
      <label><input type="checkbox" name="arbeitszeit[]" value="Teilzeit"> Teilzeit</label><br>
      <label><input type="checkbox" name="arbeitszeit[]" value="Schichtarbeit"> Schichtarbeit</label><br>
      <label><input type="checkbox" name="arbeitszeit[]" value="Home Office"> Home Office</label><br>

      <!-- Hidden Felder -->
      <input type="hidden" name="beruf" value="<?php echo htmlspecialchars($_POST['beruf'] ?? ''); ?>">
      <input type="hidden" name="ort" value="<?php echo htmlspecialchars($_POST['ort'] ?? ''); ?>">
      <input type="hidden" name="radius" value="<?php echo htmlspecialchars($_POST['radius'] ?? ''); ?>">
      <input type="hidden" name="qualifikation" value="<?php echo htmlspecialchars($_POST['qualifikation'] ?? ''); ?>">
      <input type="hidden" name="abschluss_detail" value="<?php echo htmlspecialchars($_POST['abschluss_detail'] ?? ''); ?>">
      <input type="hidden" name="erfahrung" value="<?php echo htmlspecialchars($_POST['erfahrung'] ?? ''); ?>">

      <div class="nav-buttons">
        <a href="step-erfahrung.php" class="back-button">Zurück</a>
        <button type="submit" class="next-button">Weiter</button>
      </div>
    </form>
  </main>
</body>
</html>
