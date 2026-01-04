<?php 
session_start();
include 'db.php'; ?>

<!DOCTYPE html>
<html lang="nn">
<head>
    <meta charset="UTF-8">
    <title>Bilutleige AS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'meny.php'; ?>  <!--Inkluderer menyen øvst på sida -->

    <h1>Velkomen til Bilutleige AS</h1>
    <h2>Våre bilar</h2>

<?php
    // Hentar alle bilar frå databasen
    $sql = "SELECT * FROM bilar";
    $resultat = $kopling->query($sql);

    // Går gjennom kvar rad (bil) vi fann
    while($rad = $resultat->fetch_assoc()) {
    echo "<div class='bil-kort'>";
    // htmlspecialchars() gjer om spesialteikn til trygg HTML
    echo "<h3>" . htmlspecialchars($rad['merke']) . " " . htmlspecialchars($rad['modell']) . "</h3>";
    echo "<p>Pris per dag: " . htmlspecialchars($rad['pris']) . ",- kr</p>";
    
    // Viser bestillingsknapp berre om ein er innlogga
    if(isset($_SESSION['brukar_id'])) {
        // Sender bil-ID med i URL-en til bestillingssida
        echo "<a href='bestill.php?bil_id=" . $rad['id'] . "'><button>Leig denne bilen</button></a>";
    } else {
        echo "<p><i>Logg inn for å bestille</i></p>";
    }
    echo "</div>";
}
?>

</body>
</html>