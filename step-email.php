<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Schritt 5 – E-Mail-Adresse</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main>
    <h2>Fast geschafft! Bitte gib deine E-Mail-Adresse ein</h2>

    <form action="submit.php" method="post">

      <!-- E-Mail -->
      <input type="email" name="email" placeholder="Deine E-Mail-Adresse" required>

      <!-- Übergebene Daten aus vorherigen Schritten -->
      <input type="hidden" name="beruf" value="<?php echo htmlspecialchars($_POST['beruf'] ?? ''); ?>">
      <input type="hidden" name="ort" value="<?php echo htmlspecialchars($_POST['ort'] ?? ''); ?>">
      <input type="hidden" name="radius" value="<?php echo htmlspecialchars($_POST['radius'] ?? '25'); ?>">
      <input type="hidden" name="qualifikation" value="<?php echo htmlspecialchars($_POST['qualifikation'] ?? ''); ?>">
      <input type="hidden" name="abschluss_detail" value="<?php echo htmlspecialchars($_POST['abschluss_detail'] ?? ''); ?>">
      <input type="hidden" name="erfahrung" value="<?php echo htmlspecialchars($_POST['erfahrung'] ?? ''); ?>">

      <!-- Mehrfachauswahl (Checkboxes) -->
      <?php
        if (isset($_POST['arbeitszeit'])) {
          foreach ($_POST['arbeitszeit'] as $zeit) {
            echo '<input type="hidden" name="arbeitszeit[]" value="' . htmlspecialchars($zeit) . '">';
          }
        }

        if (isset($_POST['benefits'])) {
          foreach ($_POST['benefits'] as $benefit) {
            echo '<input type="hidden" name="benefits[]" value="' . htmlspecialchars($benefit) . '">';
          }
        }
      ?>

      <div class="nav-buttons">
        <a href="step-erfahrung.html" class="back-button">Zurück</a>
        <button type="submit" class="next-button">Gehaltsangebote anzeigen</button>
      </div>
    </form>
  </main>
</body>
</html>
