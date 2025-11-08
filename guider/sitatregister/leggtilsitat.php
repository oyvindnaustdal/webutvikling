<?php
    require_once "db.php"; // Brukar same databasekopling som resten av systemet

    // Koble til databasen
    $tilkobling = $conn;

    // SQL-spørring for å hente personar
    $sql = "SELECT id, namn, periode FROM person";
    $datasett = $tilkobling->query($sql);
    if (!$datasett) {
        die("Query failed: " . htmlspecialchars($tilkobling->error));
        exit;
    }

    // Sjekkar om skjemaet er sendt inn
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {

        // Hentar og reingjer data frå skjemaet for å unngå farleg innhald
        $tekst = trim(filter_input(INPUT_POST, "tekst", FILTER_SANITIZE_STRING));
        $personid = filter_input(INPUT_POST, "lstPerson", FILTER_VALIDATE_INT);

        // Sjekkar at begge felt faktisk har verdiar
        if (!$tekst || !$personid) {
            die("Ugyldig input – sjekk at alle felt er fylt ut.");
            exit;
        }

        // Førebur trygg SQL-setning (hindrar SQL-injeksjon)
        $stmt = $tilkobling->prepare("INSERT INTO sitat (tekst, personid) VALUES (?, ?)");
        if ($stmt) {
            // Knyter parameterane til spørringa: tekst (string) og personid (integer)
            $stmt->bind_param("si", $tekst, $personid);

            // Utfører og sjekkar om det lukkast
            if ($stmt->execute()) {
                // Vellykka: sender brukaren vidare
                header("Location: sitaterok.php");
                exit;
            } else {
                die("Feil ved lagring av sitat: " . htmlspecialchars($stmt->error));
                exit;
            }

            $stmt->close();
        } else {
            die("Prepare failed: " . htmlspecialchars($tilkobling->error));
            exit;
        }
    }

    // Lukkar tilkoblinga til slutt
    $tilkobling->close();
?>
    

<!DOCTYPE html>
<html>
    <head>
        <title>Legg til sitat</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>
        <div class="registrer-container">
            <!-- Skjema for å legge til nytt sitat -->
            <form method="post">
                <!-- Felt for tekst -->
                <label for="tekst">Tekst:</label>
                <input type="text" name="tekst" id="tekst" required />
                <br />

                <!-- Nedtrekksliste med personar -->
                <label for="lstPerson">Person:</label>
                <select name="lstPerson" id="lstPerson" required>
                    <?php while($rad = mysqli_fetch_array($datasett)) { ?>
                        <!-- Brukar htmlspecialchars for å hindre XSS ved visning -->
                        <option value="<?php echo htmlspecialchars($rad["id"]); ?>">
                            <?php echo htmlspecialchars($rad["namn"]); ?> 
                            (<?php echo htmlspecialchars($rad["periode"]); ?>)
                        </option>
                    <?php } ?>
                </select>
                <br /><br />

                <!-- Send-knapp -->
                <input type="submit" name="submit" value="Legg til sitat" />
            </form>
        </div>
    </body>
</html>
