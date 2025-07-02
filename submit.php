<?php
// Datenbankverbindung
$host = "db5018128795.hosting-data.io";
$dbname = "dbs14385567";
$username = "dbu3489304";
$password = "DEIN_PASSWORT_HIER"; // <---- HIER DEIN DB-PASSWORT EINFÜGEN

$conn = new mysqli($host, $username, $password, $dbname);

// Verbindung prüfen
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// Eingabedaten absichern
function clean($data) {
    return htmlspecialchars(trim($data));
}

// Daten aus dem Formular abrufen
$beruf = clean($_POST['beruf'] ?? '');
$ort = clean($_POST['ort'] ?? '');
$radius = intval($_POST['radius'] ?? 25);
$qualifikation = clean($_POST['qualifikation'] ?? '');
$abschluss_detail = clean($_POST['abschluss_detail'] ?? '');
$erfahrung = clean($_POST['erfahrung'] ?? '');
$arbeitszeit = isset($_POST['arbeitszeit']) ? implode(', ', $_POST['arbeitszeit']) : '';
$benefits = isset($_POST['benefits']) ? implode(', ', $_POST['benefits']) : '';
$email = clean($_POST['email'] ?? '');

// Pflichtfelder prüfen
if (empty($beruf) || empty($ort) || empty($qualifikation) || empty($erfahrung) || empty($email)) {
    die("Fehler: Bitte fülle alle Pflichtfelder aus.");
}

// Daten in die Datenbank einfügen
$stmt = $conn->prepare("INSERT INTO bewerber (beruf, ort, radius, qualifikation, abschluss_detail, erfahrung, arbeitszeit, benefits, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssissssss", $beruf, $ort, $radius, $qualifikation, $abschluss_detail, $erfahrung, $arbeitszeit, $benefits, $email);

if ($stmt->execute()) {
    // Nach dem Speichern zur Danke-Seite weiterleiten
    header("Location: danke.html");
    exit();
} else {
    echo "Fehler beim Speichern: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
