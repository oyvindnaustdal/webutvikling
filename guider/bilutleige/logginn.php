<?php 
session_start();
include 'db.php'; 

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $epost = $_POST['epost'];
    $passord = $_POST['passord'];

    // Hentar brukaren basert på e-post
    $stmt = $kopling->prepare("SELECT id, namn, passord,rolle FROM brukarar WHERE epost = ?");
    $stmt->bind_param("s", $epost);
    $stmt->execute();
    $resultat = $stmt->get_result();
    
    if($rad = $resultat->fetch_assoc()) {
        // Sjekkar om passordet stemmer med hashen i databasen
        if(password_verify($passord, $rad['passord'])) {
            // Innlogging vellukka - lagrar info i session
            $_SESSION['brukar_id'] = $rad['id'];
            $_SESSION['namn'] = $rad['namn'];
            $_SESSION['rolle'] = $rad['rolle']; // NY: Lagrar rolla (admin/kunde)
            
            header("Location: index.php"); // Sender brukaren til forsida
            exit();
        } else {
            $feil = "Feil passord.";
        }
    } else {
        $feil = "Fann ingen brukar med den e-posten.";
    }
}
?>

<!DOCTYPE html>
<html lang="nn">
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <h1>Logg inn</h1>
    <?php if(isset($feil)) echo "<p class='feil'>$feil</p>"; ?>
    
    <form method="POST">
        <label>E-post:</label>
        <input type="email" name="epost" required>
        
        <label>Passord:</label>
        <input type="password" name="passord" required>
        
        <button type="submit">Logg inn</button>
    </form>
    <a href="index.php">Tilbake</a>
</body>
</html>