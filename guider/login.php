<?php
session_start(); 
// Startar ei sesjon slik at vi kan lagre og hente informasjon på tvers av sider,
// til dømes om brukaren er logga inn eller feilmeldingar frå førre forsøk.

// Dersom brukaren allereie er logga inn, send vidare til administrasjonssida
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    header("Location: personadministrasjonInnlogging.php"); 
    // header() sender ein HTTP-redirect, slik at brukaren ikkje får sjå innloggingssida på nytt.
    exit; // Stoppar vidare køyre av skriptet etter redirect
}

// Hentar eventuell feilmelding frå førre innloggingsforsøk
$error = "";
if (isset($_SESSION["error"])) {
    $error = $_SESSION["error"]; // Hentar feilmeldinga
    unset($_SESSION["error"]);   // Fjernar meldinga slik at ho ikkje visast igjen ved oppfrisking
}
?>

<!DOCTYPE html>
<html lang="nn">
<head>
    <meta charset="UTF-8">
    <title>Innlogging</title>

    <!-- Knyter til eksternt stilark -->
    <link rel="stylesheet" href="stil.css">
</head>
<body>

<div class="registrer-container">
    <h2>Logg inn</h2>

    <!-- Viser feilmelding dersom det finst ei -->
    <?php if ($error): ?>
        <!-- htmlspecialchars() hindrar XSS ved å unngå at HTML blir tolka som kode -->
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Innloggingsskjema som sender brukarnamn og passord til loginProsess.php -->
    <form action="loginProsess.php" method="POST">
        <label for="username">Brukarnamn</label>
        <input type="text" id="username" name="username" required>
        <!-- required hindrar at skjemaet blir sendt utan innhald -->

        <label for="password">Passord</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Logg inn">
    </form>
</div>

</body>
</html>
