<?php
session_start();
include 'db.php';

// SJEKKER OM BRUKAR ER ADMIN
// Viss ikkje admin, send tilbake til framsida
if (!isset($_SESSION['rolle']) || $_SESSION['rolle'] != 'admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nn">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="index.php">Tilbake til butikken</a>
        <a href="loggut.php">Logg ut</a>
    </nav>

    <h1>Admin Panel</h1>
    <p>Velkomen admin. Her kan du administrere bilparken.</p>
    
    <a href="admin_leggtil.php"><button>+ Legg til ny bil</button></a>
    
    <h2>Oversikt over bilar</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Merke</th>
            <th>Modell</th>
            <th>Pris</th>
            <th>Handlingar</th>
        </tr>
        <?php
        $sql = "SELECT * FROM bilar";
        $resultat = $kopling->query($sql);

        while($bil = $resultat->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $bil['id'] . "</td>";
            echo "<td>" . htmlspecialchars($bil['merke']) . "</td>";
            echo "<td>" . htmlspecialchars($bil['modell']) . "</td>";
            echo "<td>" . htmlspecialchars($bil['pris']) . " kr</td>";
            echo "<td>";
            // Link til rediger og slett (med ID i URL)
            echo "<a href='admin_rediger.php?id=" . $bil['id'] . "'>Rediger</a> | ";
            // JavaScript 'confirm' for å hindre uhell
            echo "<a href='admin_slett.php?id=" . $bil['id'] . "' onclick=\"return confirm('Er du sikker?');\" style='color:red;'>Slett</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>