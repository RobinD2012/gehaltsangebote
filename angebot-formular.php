<?php
// Token aus der URL holen
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Fehlender Token.");
}
$token = htmlspecialchars($_GET['token']);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Gehaltsangebot einreichen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #444;
        }
        form {
            background: white;
            padding: 25px;
            border-radius: 8px;
            max-width: 700px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label {
            font-weight: bold;
            margin-top: 15px;
            display: block;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 4px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .flex-row {
            display: flex;
            gap: 10px;
        }
        .flex-row > div {
            flex: 1;
        }
        .hidden {
            display: none;
        }
        .add-button {
            margin-top: 8px;
            display: inline-block;
            padding: 6px 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .checkbox-list label {
            font-weight: normal;
            margin-right: 10px;
        }
        button[type="submit"] {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #28a745;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h1>Gehaltsangebot einreichen</h1>

<form method="POST" action="angebot-einreichen.php">

    <!-- Token versteckt -->
    <input type="hidden" name="token" value="<?php echo $token; ?>">

    <label for="beruf">Beruf:</label>
    <input type="text" name="beruf" id="beruf" required>

    <label for="stellenprofil">Kurzes Stellenprofil (optional):</label>
    <textarea name="stellenprofil" id="stellenprofil" rows="3"></textarea>

    <label for="arbeitsbeginn">Möglicher Arbeitsbeginn ab:</label>
    <input type="date" name="arbeitsbeginn" id="arbeitsbeginn" required>

    <label>Mögliches Gehalt (€):</label>
    <div class="flex-row">
        <div><input type="number" name="gehalt_von" id="gehalt_von" placeholder="von" required></div>
        <div><input type="number" name="gehalt_bis" id="gehalt_bis" placeholder="bis" required></div>
    </div>
    <small id="gehaltWarnung" style="color:red; display:none;">Maximal 20 % Unterschied erlaubt!</small>

    <label>Jahressonderzahlung?</label>
    <select id="sonderzahlung_auswahl">
        <option value="nein">Nein</option>
        <option value="ja">Ja</option>
    </select>

    <div id="sonderzahlungen_container" class="hidden">
        <div><input type="text" name="sonderzahlungen[]" placeholder="z. B. Weihnachtsgeld"></div>
        <button type="button" class="add-button" onclick="addSonderzahlung()">+ weitere Jahressonderzahlung hinzufügen</button>
    </div>

    <label>Benefits:</label>
    <div class="checkbox-list">
        <label><input type="checkbox" name="benefits[]" value="Homeoffice"> Homeoffice</label>
        <label><input type="checkbox" name="benefits[]" value="Diensthandy"> Diensthandy</label>
        <label><input type="checkbox" name="benefits[]" value="Weiterbildung"> Weiterbildung</label>
        <label><input type="checkbox" name="benefits[]" value="Jobticket"> Jobticket</label>
    </div>

    <label>Weitere Benefits:</label>
    <div id="benefits_container">
        <div><input type="text" name="benefits[]" placeholder="z. B. Zuschuss Fitnessstudio"></div>
    </div>
    <button type="button" class="add-button" onclick="addBenefit()">+ weiteres Benefit hinzufügen</button>

    <button type="submit">Angebot übermitteln</button>
</form>

<script>
    // Sonderzahlung Feld sichtbar machen
    document.getElementById('sonderzahlung_auswahl').addEventListener('change', function () {
        const container = document.getElementById('sonderzahlungen_container');
        if (this.value === 'ja') {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    });

    // Zusatzfelder für Sonderzahlungen
    let sonderzahlungsFelder = 1;
    function addSonderzahlung() {
        if (sonderzahlungsFelder >= 10) return;
        const container = document.getElementById('sonderzahlungen_container');
        const input = document.createElement('input');
        input.type = "text";
        input.name = "sonderzahlungen[]";
        input.placeholder = "Weitere Sonderzahlung";
        container.insertBefore(input, container.lastElementChild);
        sonderzahlungsFelder++;
    }

    // Zusatzfelder für Benefits
    let benefitFelder = 1;
    function addBenefit() {
        if (benefitFelder >= 20) return;
        const container = document.getElementById('benefits_container');
        const div = document.createElement('div');
        const input = document.createElement('input');
        input.type = "text";
        input.name = "benefits[]";
        input.placeholder = "Weiteres Benefit";
        div.appendChild(input);
        container.appendChild(div);
        benefitFelder++;
    }

    // Gehaltsprüfung: max. 20 % Unterschied
    document.getElementById('gehalt_bis').addEventListener('input', function () {
        const von = parseFloat(document.getElementById('gehalt_von').value);
        const bis = parseFloat(this.value);
        const warnung = document.getElementById('gehaltWarnung');
        if (!isNaN(von) && !isNaN(bis) && bis > von) {
            const diff = bis - von;
            const prozent = diff / von;
            warnung.style.display = (prozent > 0.2) ? 'inline' : 'none';
        } else {
            warnung.style.display = 'none';
        }
    });
</script>

</body>
</html>
