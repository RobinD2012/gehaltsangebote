<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Schritt 3 – Qualifikation</title>
  <link rel="stylesheet" href="style.css">
  <script>
    function toggleAbschlussDetail() {
      const qualifikation = document.getElementById("qualifikation").value;
      const detailField = document.getElementById("abschluss_detail");

      if (qualifikation === "kein_abschluss") {
        detailField.required = false;
        detailField.style.display = "none";
      } else {
        detailField.required = true;
        detailField.style.display = "block";
      }
    }
  </script>
</head>
<body>
  <main>
    <h2>Welche Qualifikation hast du?</h2>
    <form action="step-erfahrung.php" method="post">
      <select name="qualifikation" id="qualifikation" onchange="toggleAbschlussDetail()" required>
        <option value="">Bitte wählen…</option>
        <option value="kein_abschluss">Kein Abschluss</option>
        <option value="ausbildung">Ausbildung</option>
        <option value="meister">Meister</option>
        <option value="bachelor">Bachelor</option>
        <option value="master">Master</option>
        <option value="promotion">Promotion</option>
      </select>

      <input type="text" id="abschluss_detail" name="abschluss_detail" placeholder="z. B. Bachelor Bauingenieurwesen" required>

      <!-- Hidden Felder -->
      <input type="hidden" name="beruf" value="<?php echo htmlspecialchars($_POST['beruf'] ?? ''); ?>">
      <input type="hidden" name="ort" value="<?php echo htmlspecialchars($_POST['ort'] ?? ''); ?>">
      <input type="hidden" name="radius" value="<?php echo htmlspecialchars($_POST['radius'] ?? '25'); ?>">

      <div class="nav-buttons">
        <a href="step-ort.php" class="back-button">Zurück</a>
        <button type="submit" class="next-button">Weiter</button>
      </div>
    </form>
  </main>
</body>
</html>
