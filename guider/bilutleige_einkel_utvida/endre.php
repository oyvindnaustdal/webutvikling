<?php
include 'db.php';

// 1. HENT ID FRÅ NETTADRESSA
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hent informasjonen om AKKURAT denne bilen
    $sql = "SELECT * FROM bilar WHERE id = $id";
    $resultat = $kopling->query($sql);
    $bil = $resultat->fetch_assoc();
}

// 2. OPPDATER NÅR KNAPPEN BLIR TRYKT
if (isset($_POST['oppdater'])) {
    $nytt_merke = $_POST['merke'];
    $ny_modell = $_POST['modell'];
    $ny_pris = $_POST['pris'];
    $ny_bilete = $_POST['bilete'];
    $id_som_skal_endrast = $_POST['id']; // Vi hentar ID frå eit skjult felt

    $sql = "UPDATE bilar SET merke='$nytt_merke', modell='$ny_modell', pris='$ny_pris', bilete='$ny_bilete' WHERE id=$id_som_skal_endrast";
    $kopling->query($sql);

    // Send brukaren tilbake til framsida
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nn">
<head>
    <meta charset="UTF-8">
    <title>Endre bil</title>
    <style>body { font-family: sans-serif; padding: 20px; }</style>
</head>
<body>

    <h1>Endre bil</h1>
    
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $bil['id']; ?>">

        <label>Bilmerke:</label><br>
        <input type="text" name="merke" value="<?php echo $bil['merke']; ?>"><br><br>

        <label>Modell:</label><br>
        <input type="text" name="modell" value="<?php echo $bil['modell']; ?>"><br><br>

        <label>Pris:</label><br>
        <input type="number" name="pris" value="<?php echo $bil['pris']; ?>"><br><br>

        <label>Bilete (URL):</label><br>
        <input type="text" name="bilete" value="<?php echo $bil['bilete']; ?>"><br><br>

        <button type="submit" name="oppdater">Lagre endringar</button>
    </form>
    
    <br>
    <a href="index.php">Avbryt (Gå tilbake)</a>

</body>
</html>