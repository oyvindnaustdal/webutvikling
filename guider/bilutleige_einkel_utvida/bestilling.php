<?php
include 'db.php';


// --- DEL 1: HENT BILEN VI SKAL LEIGE (Når vi kjem frå index.php) ---
if (isset($_GET['id'])) {
    $bil_id = $_GET['id'];
    
    // Vi spør databasen: "Gje meg info om bilen med denne ID-en"
    $resultat = $kopling->query("SELECT * FROM bilar WHERE id = $bil_id");
    $bil = $resultat->fetch_assoc();
}

// --- DEL 2: LAGRE BESTILLINGA (Når brukaren trykkar knappen i skjemaet) ---
if (isset($_POST['fullfor_leige'])) {
    // 1. Hent data frå skjemaet
    $bil_id_fra_skjema = $_POST['bil_id'];
    $kunde_id_fra_skjema = $_POST['kunde_id'];

    // 2. Oppdater bilen til "Utleigd" (Skiltet i ruta)
    $sql_status = "UPDATE bilar SET status='Utleigd' WHERE id=$bil_id_fra_skjema";
    $kopling->query($sql_status);

    //3. Skriv i loggboka (Historikk-tabellen)
    $sql_logg = "INSERT INTO bestillingar (bil_id, kunde_id) VALUES ($bil_id_fra_skjema, $kunde_id_fra_skjema)";
    $kopling->query($sql_logg);

    // 4. Send oss heim til framsida
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nn">
<head>
    <meta charset="UTF-8">
    <title>Bestill Bil</title>
    <link rel="stylesheet" href="stil.css">
    
    
</head>
<body>

    <h1>Ny bestilling</h1>

    <div class="boks">
        <h3>Du vil leige: <?php echo $bil['merke']; ?></h3>
        <!-- <p>Eigar av bilen: <?php echo $kundar['namn']; ?></p> -->

        <form method="POST">
            <input type="hidden" name="bil_id" value="<?php echo $bil['id']; ?>">

            <label>Vel kunde:</label><br>
            <select name="kunde_id" required>
                <option value="">-- Velg person --</option>
                <?php
                // Hent alle kundar til nedtrekksmenyen
                $kundar = $kopling->query("SELECT * FROM kundar");
                while ($k = $kundar->fetch_assoc()) {
                    // Value er ID-en (til databasen), teksten er namnet (til brukaren)
                    echo "<option value='" . $k['id'] . "'>" . $k['namn'] . "</option>";
                }
                ?>
            </select>
            <br><br>

            <button type="submit" name="fullfor_leige">Fullfør leige</button>
        </form>
    </div>

    <br>
    <a href="index.php">Avbryt (Gå tilbake)</a>

</body>
</html>