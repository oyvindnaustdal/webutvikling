<?php
session_start();
include 'db.php';

if (!isset($_SESSION['rolle']) || $_SESSION['rolle'] != 'admin') {
    header("Location: index.php"); exit();
}

$id = $_GET['id']; // Hentar ID frå URL

// 1. Viss skjema er sendt inn: Oppdater databasen
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $merke = $_POST['merke'];
    $modell = $_POST['modell'];
    $pris = $_POST['pris'];
    $bilete = $_POST['bilete'];

    $stmt = $kopling->prepare("UPDATE bilar SET merke=?, modell=?, pris=?, bilete=? WHERE id=?");
    $stmt->bind_param("ssisi", $merke, $modell, $pris, $bilete, $id);

    if ($stmt->execute()) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Feil ved lagring.";
    }
}

// 2. Hent eksisterande data for å fylle ut skjemaet
$stmt = $kopling->prepare("SELECT * FROM bilar WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$bil = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="nn">
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <h1>Rediger bil</h1>
    <form method="POST">
        <label>Merke:</label>
        <input type="text" name="merke" value="<?php echo htmlspecialchars($bil['merke']); ?>" required>
        
        <label>Modell:</label>
        <input type="text" name="modell" value="<?php echo htmlspecialchars($bil['modell']); ?>" required>
        
        <label>Døgnpris:</label>
        <input type="number" name="pris" value="<?php echo htmlspecialchars($bil['pris']); ?>" required>
        
        <label>Biletnamn:</label>
        <input type="text" name="bilete" value="<?php echo htmlspecialchars($bil['bilete']); ?>">
        
        <button type="submit">Oppdater bil</button>
    </form>
    <a href="admin.php">Avbryt</a>
</body>
</html>