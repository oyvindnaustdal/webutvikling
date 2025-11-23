<?php
session_start();
// Startar sesjonen slik at vi kan lagre feilmeldingar og innloggingsstatus på tvers av sider.

require_once "db.php";
// Hentar databasekoplinga frå ein separat fil (god praksis for struktur og tryggleik).

// Hentar brukarnamn og passord frå POST-variablane.
// Bruker null-coalescing-operator (??) for å unngå "undefined index"-feil.
$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";

// Sjekkar om felt manglar innhald
if (empty($username) || empty($password)) {
    $_SESSION["error"] = "Du må skrive inn både brukarnamn og passord.";
    // Lagrar feilmelding og sender brukaren tilbake til innloggingssida
    header("Location: login.php");
    exit;
}

// SQL-spørring for å hente brukaren basert på brukarnamn.
// Merk: vi brukar prepared statements for å hindre SQL-injeksjon.
$sql = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
// Bindar parameteren (s = string)
$stmt->bind_param("s", $username);
// Utfører spørringa
$stmt->execute();

// Hentar resultatet av spørringa
$result = $stmt->get_result();

// Sjekkar om vi fann ein brukar med dette brukarnamnet
if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // password_verify() sjekkar om passordet (i klartekst) passar med den hasha varianten i databasen.
    if (password_verify($password, $row["password"])) {
        // Passordet er korrekt — logg inn brukaren
        $_SESSION["logged_in"] = true;
        $_SESSION["username"] = $row["username"];
        // Send brukaren vidare til admin-sida
        header("Location: personadministrasjonInnlogging.php");
        exit;
    } else {
        // Feil passord
        $_SESSION["error"] = "Feil passord. Prøv igjen.";
    }

} else {
    // Brukarnamnet finst ikkje i databasen
    $_SESSION["error"] = "Dette brukarnamnet finst ikkje.";
}

// Lukkar statement og databasekopling
$stmt->close();
$conn->close();

// Sender brukaren tilbake til innloggingssida etter feil
header("Location: login.php");
exit;
?>
