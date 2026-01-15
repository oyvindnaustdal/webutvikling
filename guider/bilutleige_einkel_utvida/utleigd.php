<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="nn">
<head>
    <meta charset="UTF-8">
    <title>Oversikt Utleigde Bilar</title>
    <link rel="stylesheet" href="stil.css">
    
</head>
<body>

    <a href="index.php" class="tilbake">← Tilbake til framsida</a>

    <h1>Bilar som er utleigde akkurat no</h1>
    <p>Her er oversikta over kven som har bilane våre.</p>

    <table>
        <tr>
            <th>Bilmerke</th>
            <th>Eigar av bilen</th>
            <th>Kunde (Leigetakar)</th>
            <th>Mobilnummer</th>
            <th>Status</th>
        </tr>

        <?php
        // SQL: Hent bil + kunde, MEN BERRE dei som er 'Utleigd'
        $sql = "SELECT bilar.merke, bilar.modell, kundar.namn, kundar.mobil, bilar.status 
                FROM bilar, bestillingar 
                JOIN kundar ON bestillingar.kunde_id = kundar.id
                WHERE bilar.status = 'Utleigd'";

        $resultat = $kopling->query($sql);

        // Sjekk om det er tomt (ingen bilar utleigd)
        if ($resultat->num_rows == 0) {
            echo "<tr><td colspan='5'>Ingen bilar er utleigde akkurat no. </td></tr>";
        } else {
            // Vis lista
            while ($rad = $resultat->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $rad['merke'] . "</td>";
                echo "<td>" . $rad['modell'] . "</td>";
                echo "<td><strong>" . $rad['namn'] . "</strong></td>";
                echo "<td>" . $rad['mobil'] . "</td>";
                echo "<td style='color:red;'>" . $rad['status'] . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>

    <br>
    // skrive ut på skrivar
    <button onclick="window.print()">Skriv ut liste</button>

</body>
</html>