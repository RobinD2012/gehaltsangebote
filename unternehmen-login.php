<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Unternehmen Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="form-container">
    <h2>Unternehmens-Login</h2>

    <form action="unternehmen-login-verarbeiten.php" method="post">
      <label for="email">E-Mail-Adresse</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Passwort</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Einloggen</button>
    </form>

    <p style="margin-top: 15px; text-align: right;">
      <a href="passwort-vergessen.php" style="font-size: 0.9em;">Passwort vergessen?</a>
    </p>

    <p style="margin-top:20px; font-size: 0.9em;">
      Noch kein Konto? <a href="unternehmen-registrierung.php">Jetzt registrieren</a>
    </p>
  </main>
</body>
</html>
