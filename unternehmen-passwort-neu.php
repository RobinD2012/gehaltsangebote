<?php
$token = $_GET['token'] ?? '';
if (!$token) die("Ungültiger Link");
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Passwort neu setzen</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="form-container">
    <h2>Neues Passwort festlegen</h2>
    <form action="unternehmen-passwort-speichern.php" method="post">
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

      <label for="pass1">Neues Passwort</label>
      <input type="password" name="pass1" id="pass1" required>

      <label for="pass2">Passwort bestätigen</label>
      <input type="password" name="pass2" id="pass2" required>

      <button type="submit">Passwort speichern</button>
    </form>
  </main>
</body>
</html>
