<?php
session_start();
// Startar sesjonen for å kunne hente og vise meldingar frå registreringsforsøket.

// Hentar eventuelle feilmeldingar og suksessmeldingar frå førre forsøk.
// Dersom ingen meldingar finst, blir variablane sett til tom streng.
$error = $_SESSION["error"] ?? "";
$success = $_SESSION["success"] ?? "";

// Vi fjernar meldingane etter at dei er henta, slik at same melding ikkje blir vist igjen
unset($_SESSION["error"]);
unset($_SESSION["success"]);
?>

<!DOCTYPE html>
<html lang="nn">
<head>
    <meta charset="UTF-8">
    <title>Registrer ny brukar</title>

    <!-- Knyter til eksternt stilark for design -->
    <link rel="stylesheet" href="stil.css">
</head>
<body>

<div class="registrer-container">
    <h2>Registrer ny brukar</h2>

    <!-- Dersom ei feilmelding finst, vis henne -->
    <?php if ($error): ?>
        <!-- htmlspecialchars() hindrar XSS ved å gjere spesialteikn trygge -->
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Dersom ein suksessmelding finst, vis henne -->
    <?php if ($success): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <!-- Skjema for registrering av ny brukar -->
    <!-- Sender data til registrerProsess.php med POST (sikrare enn GET for passord) -->
    <form action="registrerProsess.php" method="POST">
        <label for="username">Brukarnamn</label>
        <input type="text" id="username" name="username" required>
        <!-- required hindrar at feltet blir sendt tomt -->

        <label for="password">Passord</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Gjenta passord</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <input type="submit" value="Opprett brukar">
    </form>

    <!-- Informasjon og lenkje for brukarar som alt har ein konto -->
    <p>Har du alt ein konto? <a href="login.php">Logg inn her</a></p>
</div>

</body>
</html>
