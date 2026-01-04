<?php 
session_start();
include 'db.php'; 

// Sjekk tilgang
if(!isset($_SESSION['brukar_id'])) {
    header("Location: logginn.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="nn">
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <nav>
        <a href="index.php">Heim</a>
        <a href="loggut.php">Logg ut</a>
    </nav>

    <h1>Hei, <?php echo htmlspecialchars($_SESSION['namn']); ?></h1>
    <h2>Dine bestillingar</h2>

    <?php
    $brukar_id = $_SESSION['brukar_id'];
    
    // JOIN-spørjing:
    // Vi hentar info frå bestillingstabellen OG info frå biltabellen samstundes.
    // "ON bestillingar.bil_id = bilar.id" fortel databasen kva som heng saman.
    $sql = "SELECT bestillingar.*, bilar.merke, bilar.modell 
            FROM bestillingar 
            JOIN bilar ON bestillingar.bil_id = bilar.id 
            WHERE bestillingar.brukar_id = ?";
            
    $stmt = $kopling->prepare($sql);
    $stmt->bind_param("i", $brukar_id);
    $stmt->execute();
    $resultat = $stmt->get_result();

    if($resultat->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Bil</th><th>Frå</th><th>Til</th></tr>";
        
        while($rad = $resultat->fetch_assoc()) {
            //echo "<tr>";
            echo "<td>" . htmlspecialchars($rad['merke'] . " " . $rad['modell']) . "</td>";
            echo "<td>" . htmlspecialchars($rad['fra_dato']) . "</td>";
            echo "<td>" . htmlspecialchars($rad['til_dato']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Du har ingen bestillingar enno.</p>";
    }
    ?>
</body>
</html>