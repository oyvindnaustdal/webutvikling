<?php 
session_start();
include 'db.php'; 

// Sjekkar om brukar er innlogga. Viss ikkje, kast dei ut.
if(!isset($_SESSION['brukar_id'])) {
    header("Location: logginn.php");
    exit();
}

// Hentar bil-ID frå URL-en (f.eks bestill.php?bil_id=1)
$bil_id = $_GET['bil_id'];

// Hentar info om bilen for å vise det på skjermen
$stmt = $kopling->prepare("SELECT * FROM bilar WHERE id = ?"); // Hindrar SQL-injection
$stmt->bind_param("i", $bil_id);
$stmt->execute();
$bil = $stmt->get_result()->fetch_assoc();

$feilmelding = ""; // Variabel for å lagre feilmeldingar


if($_SERVER["REQUEST_METHOD"] == "POST") {
    $fra_dato = $_POST['fra_dato'];
    $til_dato = $_POST['til_dato'];
    $brukar_id = $_SESSION['brukar_id']; // Hentar ID frå innlogga brukar

    // 1. Enkel sjekk: Er 'til dato' før 'frå dato'?
    if($fra_dato > $til_dato) {
        $feilmelding = "Til-dato kan ikkje vere før frå-dato.";
    } else {
        // 2. SQL-SJEKK FOR DOBBELTBOOKING
        // Vi leitar etter bestillingar for DENNE bilen der tidsrommet overlappar
        // Logikk: (Eksisterande start <= Ny slutt) OG (Eksisterande slutt >= Ny start)
        
        $sjekk_sql = "SELECT id FROM bestillingar 
                      WHERE bil_id = ? 
                      AND fra_dato <= ? 
                      AND til_dato >= ?";
        
        $stmt_sjekk = $kopling->prepare($sjekk_sql);
        $stmt_sjekk->bind_param("iss", $bil_id, $til_dato, $fra_dato);
        $stmt_sjekk->execute();
        $stmt_sjekk->store_result();

        if($stmt_sjekk->num_rows > 0) {
            // Vi fann ein treff, det betyr at bilen er opptatt
            $feilmelding = "Desverre, bilen er allereie utleigd i denne perioden.";
        } else {
            // 3. Ingen krasj, vi kan gjennomføre bestillinga
            // Lagrar bestillinga
            $insert = $kopling->prepare("INSERT INTO bestillingar (brukar_id, bil_id, fra_dato, til_dato) VALUES (?, ?, ?, ?)");
            $insert->bind_param("iiss", $brukar_id, $bil_id, $fra_dato, $til_dato);

            if($insert->execute()) {
            header("Location: minside.php"); // Send til "mi side" etter bestilling
                exit();
            } else {
                echo "<p class='feil'>Feil ved bestilling.</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nn">
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <h1>Bestill <?php echo htmlspecialchars($bil['merke'] . " " . $bil['modell']); ?></h1>

    <?php if($feilmelding != ""): ?>
        <p class="feil"><?php echo $feilmelding; ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <label>Frå dato:</label>
        <input type="date" name="fra_dato" required>
        
        <label>Til dato:</label>
        <input type="date" name="til_dato" required>
        
        <button type="submit">Stadfest bestilling</button>
    </form>
    <a href="index.php">Avbryt</a>
</body>
</html>