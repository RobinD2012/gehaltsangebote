<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Schritt – Benefits</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main>
    <h2>Was ist dir bei der Arbeit besonders wichtig?</h2>
    <form action="step-email.php" method="post">

      <label><input type="checkbox" name="benefits[]" value="Weiterbildung"> Weiterbildung</label><br>
      <label><input type="checkbox" name="benefits[]" value="Work-Life-Balance"> Work-Life-Balance</label><br>
      <label><input type="checkbox" name="benefits[]" value="Sicherer Arbeitsplatz"> Sicherer Arbeitsplatz</label><br>
      <label><input type="checkbox" name="benefits[]" value="Flexible Arbeitszeiten"> Flexible Arbeitszeiten</label><br>

      <!-- Hidden Felder -->
      <input type="hidden" name="beruf" value="<?php echo htmlspecialchars($_POST['beruf'] ?? ''); ?>">
      <input type="hidden" name="ort" value="<?php echo htmlspecialchars($_POST['ort'] ?? ''); ?>">
      <input type="hidden" name="radius" value="<?php echo htmlspecialchars($_POST['radius'] ?? ''); ?>">
      <input type="hidden" name="qualifikation" value="<?php echo htmlspecialchars($_POST['qualifikation'] ?? ''); ?>">
      <input type="hidden" name="abschluss_detail" value="<?php echo htmlspecialchars($_POST['abschluss_detail'] ?? ''); ?>">
      <input type="hidden" name="erfahrung" value="<?php echo htmlspecialchars($_POST['erfahrung'] ?? ''); ?>">
      
      <?php
        // Arbeitszeit-Werte weitergeben
        if (!empty($_POST['arbeitszeit'])) {
          foreach ($_POST['arbeitszeit'] as $zeit) {
            echo '<input type="hidden" name="arbeitszeit[]" value="' . htmlspecialchars($zeit) . '">' . "\n";
          }
        }
      ?>

      <div class="nav-buttons">
        <a href="step-arbeitszeit.php" class="back-button">Zurück</a>
        <button type="submit" class="next-button">Weiter</button>
      </div>
    </form>
  </main>
</body>
</html>
