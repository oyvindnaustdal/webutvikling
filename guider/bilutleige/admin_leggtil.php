<?php
session_start();
include 'db.php';

// Sjekk tilgang
if (!isset($_SESSION['rolle']) || $_SESSION['rolle'] != 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $merke = $_POST['merke'];
    $modell = $_POST['modell'];
    $pris = $_POST['pris'];
    $bilete = $_POST['bilete']; // Her skriv vi berre filnamnet (f.eks yaris.jpg) for enkelheits skuld

    $stmt = $kopling->prepare("INSERT INTO bilar (merke, modell, pris, bilete) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $merke, $modell, $pris, $bilete);

    if ($stmt->execute()) {
        header("Location: admin.php"); // Gå tilbake til oversikta
        exit();
    } else {
        $feil = "Kunne ikkje lagre bilen.";
    }
}
?>

<!DOCTYPE html>
<html lang="nn">
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <h1>Legg til ny bil</h1>
    <form method="POST">
        <label>Merke:</label>
        <input type="text" name="merke" required>
        
        <label>Modell:</label>
        <input type="text" name="modell" required>
        
        <label>Døgnpris:</label>
        <input type="number" name="pris" required>
        
        <label>Biletnamn (f.eks 'volvo.jpg'):</label>
        <input type="text" name="bilete">
        
        <button type="submit">Lagre bil</button>
    </form>
    <a href="admin.php">Avbryt</a>
</body>
</html>