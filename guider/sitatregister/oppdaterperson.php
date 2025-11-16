<?php

require_once "db.php"; // Brukar same databasekopling som resten av systemet

// --- DATABASEKOPLING ---
$tilkobling = $conn; // Bruk same tilkopling frå db.php

// --- HENTAR ID FOR PERSON SOM SKAL OPPDATERAST ---
// Valider at oppdaterID faktisk er eit heiltal (hindrar SQL Injection via URL)
$oppdaterID = filter_input(INPUT_GET, "oppdaterID", FILTER_VALIDATE_INT);

if (!$oppdaterID) {
    // Stoppar koden om ID manglar eller ikkje er gyldig
    die("Ugyldig eller manglande ID.");
}

// --- HENTAR PERSONDATA FRÅ DATABASEN ---
$stmt = $tilkobling->prepare("SELECT namn, periode FROM person WHERE id = ?");
$stmt->bind_param("i", $oppdaterID);
$stmt->execute();
$datasett = $stmt->get_result();

// --- OPPDATERAR DATA OM SKJEMA BLIR SENDA INN ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {

    // Trim og rens input for unødvendige mellomrom og potensielle skadelege teikn
    $namn = trim($_POST["namn"] ?? '');
    $periode = trim($_POST["periode"] ?? '');

    // Enkelt filter for å hindre at felt står tomme
    if ($namn === '' || $periode === '') {
        die("Både namn og periode må fyllast ut.");
    }

    // Forhindre XSS ved å fjerne HTML-taggar (du kan òg bruke htmlspecialchars seinare ved vising)
    $namn = strip_tags($namn);
    $periode = strip_tags($periode);

    // Bruk prepared statement for sikker oppdatering
    $stmt = $tilkobling->prepare("UPDATE person SET namn = ?, periode = ? WHERE id = ?");
    $stmt->bind_param("ssi", $namn, $periode, $oppdaterID);
    $stmt->execute();

    // Send brukaren tilbake til administrasjonssida etter oppdatering
    header("Location: personadministrasjon.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="nn">
<head>
    <meta charset="utf-8" />
    <title>Oppdater person</title>
    <link rel="stylesheet" href="stil.css" />
</head>
<body>
    <div class="registrer-container">
    <h1>Oppdater person</h1>
     
    <form method="post">
        <?php if ($rad = $datasett->fetch_assoc()) { ?>
            <label for="namn">Namn:</label><br />
            <!-- Brukar htmlspecialchars for å hindre XSS ved vising av data -->
            <input type="text" name="namn" id="namn"
                   value="<?php echo htmlspecialchars($rad["namn"]); ?>" />
            <br /><br />

            <label for="periode">Periode:</label><br />
            <input type="text" name="periode" id="periode"
                   value="<?php echo htmlspecialchars($rad["periode"]); ?>" />
            <br /><br />

            <button type="submit" name="submit">Oppdater person</button>
        <?php } else { ?>
            <p>Ingen person funnen med denne ID-en.</p>
        <?php } ?>
    </form>
     </div>
</body>
</html>
