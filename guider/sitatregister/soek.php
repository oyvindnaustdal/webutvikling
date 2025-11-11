<?php
    // Startar ei økt slik at vi kan lagre søkeordet
    session_start();

    // Hentar databasekopling frå ekstern fil
    require_once "db.php";

    $tilkobling = $conn;

    // Kontrollerer at databasen er kopla til
    if (!$tilkobling) {
        die("Tilkobling feila: " . mysqli_connect_error());
    }

    // Sjekkar om brukaren har sendt inn skjemaet
    if(isset($_GET["submit"])) {

        // Hentar søkestrengen frå skjemaet
        $soekestreng = trim($_GET["txtSoekestreng"]);

        // Lagre søkestrengen i session (hugse førre søk)
        $_SESSION["forrige_soek"] = $soekestreng;

        // Førebur trygg SQL-spørring (hindrar SQL-injeksjon)
        $stmt = $tilkobling->prepare("SELECT tekst FROM sitat WHERE tekst LIKE ?");
        $soekemal = "%" . $soekestreng . "%";
        $stmt->bind_param("s", $soekemal);
        $stmt->execute();

        // Hentar resultata frå spørringa
        $datasett = $stmt->get_result();
    }

    // Dersom brukaren har eit tidlegare søk i session, hent det fram
    $forhandsutfylt = $_SESSION["forrige_soek"] ?? "";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Sitater</title>
    <link rel="stylesheet" href="stil.css">
</head>
<body>
    <div class="registrer-container">
    <!-- Søkjefelt med førehandutfylt tekst frå session -->
    <form method="get">
        <label for="txtSoekestreng">Søkestreng:</label>
        <input 
            type="text" 
            name="txtSoekestreng" 
            id="txtSoekestreng" 
            value="<?php echo htmlspecialchars($forhandsutfylt, ENT_QUOTES, 'UTF-8'); ?>" 
        />
        <input type="submit" name="submit" value="Søk" />
    </form>
    

    <?php
    // Dersom eit datasett er tilgjengeleg, vis resultata
    if (isset($datasett)) {
        while ($rad = mysqli_fetch_array($datasett)) {
            // htmlspecialchars hindrar XSS-angrep
            echo "<p>" . htmlspecialchars($rad["tekst"], ENT_QUOTES, 'UTF-8') . "</p>";
        }

        // Lukkar spørring og kopling
        $stmt->close();
        $tilkobling->close();
    }
    ?>
    </div>
</body>
</html>
