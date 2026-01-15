<?php
include 'db.php';

// Legg til kunde
if (isset($_POST['legg_til_kunde'])) {
    $namn = $_POST['namn'];
    $mobil = $_POST['mobil'];
    $kopling->query("INSERT INTO kundar (namn, mobil) VALUES ('$namn', '$mobil')");
}
?>

<!DOCTYPE html>
<html lang="nn">
<head>
    <meta charset="UTF-8">
    <title>Kunderegister</title>
    <link rel="stylesheet" href="stil.css">
    
</head>
<body>
    <a href="index.php">Tilbake til Biloversikt</a>
    <h1>Kunderegister</h1>

    <div style="background: #eee; padding: 10px; margin-bottom: 20px;">
        <h3>Registrer ny kunde</h3>
        <form method="POST">
            Namn: <input type="text" name="namn" required>
            Mobil: <input type="text" name="mobil" required>
            <button type="submit" name="legg_til_kunde">Lagre</button>
        </form>
    </div>

    <table border="1" cellpadding="5">
        <tr><th>ID</th><th>Namn</th><th>Mobil</th></tr>
        <?php
        $resultat = $kopling->query("SELECT * FROM kundar");
        while ($rad = $resultat->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $rad['id'] . "</td>";
            echo "<td>" . $rad['namn'] . "</td>";
            echo "<td>" . $rad['mobil'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>