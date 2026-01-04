<?php 
session_start();
include 'db.php'; 
$melding = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Hentar data frå skjemaet
    $namn = $_POST['namn'];
    $epost = $_POST['epost'];
    $passord = $_POST['passord'];

    // 1. Sjekk om e-posten allereie finst
    // Vi brukar prepare statements (?) for å hindre SQL-injeksjon
    $sjekk = $kopling->prepare("SELECT id FROM brukarar WHERE epost = ?");
    $sjekk->bind_param("s", $epost); // "s" betyr string
    $sjekk->execute();
    $sjekk->store_result();

    if($sjekk->num_rows > 0) {
        $melding = "<p class='feil'>E-posten er allereie registrert.</p>";
    } else {
        // 2. Krypter passordet (Hashing)
        // Dette gjer at sjølv om databasen blir hacka, kan ikkje passorda lesast
        $hashet_passord = password_hash($passord, PASSWORD_DEFAULT);

        // 3. Legg til ny brukar
        $stmt = $kopling->prepare("INSERT INTO brukarar (namn, epost, passord) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $namn, $epost, $hashet_passord);

        if($stmt->execute()) {
            $melding = "<p class='suksess'>Brukar oppretta! Du kan no <a href='logginn.php'>logge inn</a>.</p>";
        } else {
            $melding = "<p class='feil'>Noko gjekk gale.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nn">
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <h1>Registrer deg</h1>
    <?php echo $melding; ?>
    
    <form method="POST">
        <label>Namn:</label>
        <input type="text" name="namn" required>
        
        <label>E-post:</label>
        <input type="email" name="epost" required>
        
        <label>Passord:</label>
        <input type="password" name="passord" required>
        
        <button type="submit">Registrer</button>
    </form>
    <a href="index.php">Tilbake</a>
</body>
</html>