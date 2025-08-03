<?php
require_once 'db_connect.php';
require_once 'vendor/autoload.php'; // Pfad zu PHPMailer ggf. anpassen

// Token prüfen
if (!isset($_GET['token'])) {
    die("Kein gültiger Zugriff.");
}

$token = $_GET['token'];

// Bewerberdaten auslesen
$stmt = $conn->prepare("SELECT b.*, l.email FROM bewerber_links l JOIN bewerber b ON l.bewerber_id = b.id WHERE l.token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Token ungültig oder abgelaufen.");
}

$bewerber = $result->fetch_assoc();
$email = $bewerber['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Eingaben abfangen und validieren
    $beruf = $_POST['beruf'];
    $stellenprofil = $_POST['stellenprofil'];
    $beginn = $_POST['beginn'];
    $gehalt_von = floatval($_POST['gehalt_von']);
    $gehalt_bis = floatval($_POST['gehalt_bis']);

    if ($gehalt_bis > $gehalt_von * 1.2) {
        die("Gehaltsspanne darf maximal 20 % betragen.");
    }

    $sonderzahlungen = $_POST['sonderzahlung'] ?? [];
    $benefits = $_POST['benefit'] ?? [];

    // Mail an Bewerber senden
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.strato.de';
        $mail->SMTPAuth = true;
        $mail->Username = 'dein-smtp-user@domain.de'; // ersetzen
        $mail->Password = 'dein-passwort'; // ersetzen
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('noreply@meingehaltsvergleich.de', 'MeinGehaltsvergleich.de');
        $mail->addAddress($email); // Nicht anzeigen – nur intern verwendet
        $mail->Subject = 'Neues Gehaltsangebot für dich!';
        $mail->isHTML(true);
        $mail->Body = "
            <p>Du hast ein neues Gehaltsangebot erhalten:</p>
            <ul>
                <li><strong>Beruf:</strong> $beruf</li>
                <li><strong>Beginn ab:</strong> $beginn</li>
                <li><strong>Gehalt:</strong> $gehalt_von € – $gehalt_bis €</li>
                <li><strong>Stellenprofil:</strong><br>$stellenprofil</li>
                <li><strong>Sonderzahlungen:</strong><br>" . implode(', ', array_filter($sonderzahlungen)) . "</li>
                <li><strong>Benefits:</strong><br>" . implode(', ', array_filter($benefits)) . "</li>
            </ul>
            <p>Du kannst dich direkt auf der Plattform einloggen, um weitere Details zu sehen.</p>
        ";
        $mail->send();
        echo "<p style='font-size:20px; color:green;'>Das Angebot wurde erfolgreich übermittelt.</p>";
    } catch (Exception $e) {
        echo "Mailversand fehlgeschlagen: " . $mail->ErrorInfo;
    }
    exit;
}

// HTML-Formular anzeigen
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Gehaltsangebot einreichen</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 40px; }
        form { max-width: 700px; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 12px rgba(0,0,0,0.1); }
        h2 { color: #0077cc; }
        label { font-weight: bold; display: block; margin-top: 20px; }
        input, textarea, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 6px; border: 1px solid #ccc; }
        .flex { display: flex; gap: 10px; }
        .flex input { width: 100%; }
        .button { margin-top: 30px; background: #0077cc; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; }
        .button:hover { background: #005fa3; }
        .dynamic-field { margin-top: 10px; }
        .add-button { margin-top: 10px; background: #e0e0e0; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Gehaltsangebot einreichen</h2>

        <label>Beruf</label>
        <input type="text" name="beruf" required>

        <label>Kurzes Stellenprofil (optional)</label>
        <textarea name="stellenprofil" rows="4"></textarea>

        <label>Möglicher Arbeitsbeginn ab</label>
        <input type="date" name="beginn" value="<?= date('Y-m-d', strtotime('+14 days')) ?>" required>

        <label>Mögliches Gehalt (€)</label>
        <div class="flex">
            <input type="number" name="gehalt_von" placeholder="von" step="100" required>
            <input type="number" name="gehalt_bis" placeholder="bis" step="100" required>
        </div>

        <label>Jahressonderzahlung?</label>
        <select id="sonderzahlung-toggle">
            <option value="nein">Nein</option>
            <option value="ja">Ja</option>
        </select>

        <div id="sonderzahlung-container" style="display:none;">
            <div class="dynamic-field">
                <input type="text" name="sonderzahlung[]" placeholder="z. B. Weihnachtsgeld">
            </div>
            <button type="button" class="add-button" onclick="addField('sonderzahlung')">+ weitere Jahressonderzahlung hinzufügen</button>
        </div>

        <label>Benefits</label>
        <div>
            <label><input type="checkbox" name="benefit[]" value="Homeoffice"> Homeoffice</label><br>
            <label><input type="checkbox" name="benefit[]" value="Dienstwagen"> Dienstwagen</label><br>
            <label><input type="checkbox" name="benefit[]" value="Weiterbildung"> Weiterbildung</label>
        </div>

        <div class="dynamic-field">
            <input type="text" name="benefit[]" placeholder="weiterer Benefit">
        </div>
        <button type="button" class="add-button" onclick="addField('benefit')">+ weiteres Benefit hinzufügen</button>

        <button class="button" type="submit">Jetzt Gehaltsangebot senden</button>
    </form>

    <script>
        const toggle = document.getElementById('sonderzahlung-toggle');
        const container = document.getElementById('sonderzahlung-container');
        toggle.addEventListener('change', function () {
            container.style.display = (this.value === 'ja') ? 'block' : 'none';
        });

        function addField(name) {
            const input = document.createElement('input');
            input.type = 'text';
            input.name = name + '[]';
            input.placeholder = name === 'sonderzahlung' ? 'z. B. Bonuszahlung' : 'weiterer Benefit';
            input.className = 'dynamic-field';
            document.querySelector('#' + name + '-container')?.appendChild(input) ||
                document.querySelector('input[name="' + name + '[]"]').parentNode.appendChild(input);
        }
    </script>
</body>
</html>
