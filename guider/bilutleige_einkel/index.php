<?php
include 'db.php';

// --- DEL 1: LEGG TIL NY BIL (Viss brukaren trykka på knappen) ---
if (isset($_POST['legg_til'])) {
    $merke =$_POST['merke'];
    $modell= $_POST['modell'];
    $pris= $_POST['pris'];
    $bilete = $_POST['bilete'];

    // Einkel SQL for å sette inn data
    $sql = "INSERT INTO bilar(merke, modell, pris, bilete) VALUES ('$merke', '$modell', '$pris', '$bilete')";
    $kopling->query($sql);
}

// --- DEL 2: SLETT BIL (Viss brukaren trykka slett) ---
if (isset($_GET['slett_id'])) {
    $id = $_GET['slett_id'];
    
    // Einkel SQL for å slette
    $sql = "DELETE FROM bilar WHERE id = $id";
    $kopling->query($sql);
    
    // Lastar sida på nytt for å fjerne ID-en frå nettadressa
    header("Location: index.php"); 

}

?>

<!DOCTYPE html>
<html lang="nn">
<head>
    <meta charset="UTF-8">
    <title>Enkel Bilutleige</title>
    <link rel="stylesheet" href="stil.css">
    
</head>
<body>

    <h1>Registrer ny bil</h1>

    <div class="boks">
        <h3>Legg til ny bil</h3>
        <form method="POST">
            <label>Bilmerke:</label><br>
            <input type="text" name="merke" required><br><br>
            <label>Modell</label><br>
            <input type="text" name="modell" required><br><br>
            <label>Pris per dag:</label><br>
            <input type="number" name="pris" required><br><br
            <label>Bilete (URL):</label><br>
            <input type="text" name="bilete" ><br><br>
            
            <button type="submit" name="legg_til">Lagre bil</button>
        </form>
    </div>

    <h3>Alle bilar i systemet:</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Merke</th>
            <th>Modell</th>
            <th>Pris</th>
            <th>Bilete</th>
            <th>Rediger</th>
       </tr>

        <?php
        // Hentar alle data frå databasen
        $resultat = $kopling->query("SELECT * FROM bilar");

        // Går gjennom kvar rad (bil)
        while ($rad = $resultat->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $rad['id'] . "</td>";
            echo "<td>" . $rad['merke'] . "</td>";
            echo "<td>" . $rad['modell'] . "</td>";
            echo "<td>" . $rad['pris'] . "</td>";
            echo "<td><img src='" . $rad['bilete'] . "' alt='Bilete av bilen' width='100'></td>";

            // Slett rad
            echo "<td><a href='index.php?slett_id=" . $rad['id'] . "' class='slett'>Slett</a>";
           echo " | ";
            
           // Endre rad
            echo "<a href='endre.php?id=" . $rad['id'] . "' class='endre'>Endre</a>" ;
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>

</body>
</html>