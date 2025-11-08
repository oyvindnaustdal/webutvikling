<?php
require_once "db.php"; 
// Hentar inn fila db.php som inneheld tilkoplinga til databasen. 
// "require_once" sørgjer for at fila berre blir inkludert éin gong, 
// slik at du unngår feil ved fleire importeringar.

// Koble til databasen ved å bruke koplinga frå db.php
$tilkobling = $conn;

// Variabel for å vise melding til brukaren
$melding = "";

// Sjekkar om skjemaet er sendt inn (POST-metoden betyr at data kjem frå eit skjema)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Hentar ut verdiane frå skjemafelta og fjernar ekstra mellomrom
    $navn = trim($_POST["txtNamn"]);
    $periode = trim($_POST["txtPeriode"]);

    // Sjekkar at begge felt faktisk har verdiar (ikkje tomme)
    if ($navn && $periode) {

        // Lag førebuande SQL-setning (hindrar SQL-injeksjon)
        $stmt = $tilkobling->prepare("INSERT INTO person (namn, periode) VALUES (?, ?)");
        // Knyter variablane $navn og $periode til plasshaldarane i SQL-en (to tekststrengar "ss")
        $stmt->bind_param("ss", $navn, $periode);

        // Forsøk å køyre SQL-spørringa
        if ($stmt->execute()) {
            // Om det fungerer, gi melding til brukaren
            $melding = "Personen ble lagt til i registeret.";
        } else {
            // Om det skjer ein feil, gi ei feilmelding
            $melding = "En feil oppstod.";
        }

        // Lukkar den førebuande spørringa
        $stmt->close();
    } else {
        // Om eitt av felta er tomt, gi ei tilbakemelding
        $melding = "Vennligst fyll ut alle felt.";
    }
}

// Lukkar tilkoplinga til databasen når alt er ferdig
$tilkobling->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Legg til person</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="stil.css"> 
    <!-- Lenke til ekstern stilfil for utforming av sida -->
</head>
<body>
    <div class="registrer-container">
        <h1>Legg til person</h1>

        <!-- Viser melding til brukaren dersom $melding inneheld tekst -->
        <?php if ($melding): ?>
            <p><?php echo htmlspecialchars($melding); ?></p>
            <!-- htmlspecialchars hindrar at farleg HTML blir vist direkte -->
        <?php endif; ?>

        <!-- Skjema for å legge til ny person -->
        <form method="post">
            <label>Navn:</label><br>
            <input type="text" name="txtNamn" required><br><br>

            <label>Periode:</label><br>
            <input type="text" name="txtPeriode" required><br><br>

            <input type="submit" value="Legg til person"><br><br>
            <input type="reset" value="Tøm skjema">
        </form>
    </div>
</body>
</html>
