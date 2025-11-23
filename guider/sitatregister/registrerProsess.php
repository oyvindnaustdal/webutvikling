<?php
session_start();
// Startar sesjonen slik at vi kan lagre feilmeldingar og suksessmeldingar.

require_once "db.php";
// Hentar databasekopling frå ekstern fil (god struktur og betre tryggleik).

// Hentar verdiane frå skjemaet.
// trim() fjernar mellomrom i starten/slutten av brukarnamnet.
$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";
$confirm_password = $_POST["confirm_password"] ?? "";

// --- VALIDASJON AV INPUT --- //

// Sjekkar om nokon av felta er tomme
if (empty($username) || empty($password) || empty($confirm_password)) {
    $_SESSION["error"] = "Du må fylle ut alle felt.";
    header("Location: registrerBrukar.php");
    exit;
}

// Sjekkar om passord og gjentatt passord er like
if ($password !== $confirm_password) {
    $_SESSION["error"] = "Passorda er ikkje like. Prøv igjen.";
    header("Location: registrerBrukar.php");
    exit;
}

// --- SJEKK OM BRUKARNAMN ALT FINST --- //

$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
// Bindar brukarnamnet som ein string ("s")
$stmt->bind_param("s", $username);
$stmt->execute();
// store_result() gjer at vi kan hente antal rader utan å hente sjølve datasettet
$stmt->store_result();

// Dersom det finst minst éin rad, er brukarnamnet opptatt
if ($stmt->num_rows > 0) {
    $_SESSION["error"] = "Brukarnamnet er alt i bruk. Vel eit anna.";
    $stmt->close();
    header("Location: registrerBrukar.php");
    exit;
}
$stmt->close();

// --- OPPRETTE NY BRUKAR MED TRYGG PASSORDHASH --- //

// Hashar passordet før det blir lagra i databasen
// PASSWORD_DEFAULT vel trygg algoritme automatisk (t.d. bcrypt eller Argon2)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
// Bindar brukarnamn og hash som to string-parametrar
$stmt->bind_param("ss", $username, $hashed_password);

// Prøver å lagre brukaren i databasen
if ($stmt->execute()) {
    // Lagra suksessmelding som visast på registreringssida
    $_SESSION["success"] = "Brukaren vart oppretta! Du kan no logge inn.";
} else {
    // Dersom noko går gale (t.d. databasefeil)
    $_SESSION["error"] = "Noko gjekk gale under registreringa.";
}

$stmt->close();
$conn->close();

// Sender brukaren tilbake til registreringssida, enten med suksess- eller feilmelding
header("Location: registrerBrukar.php");
exit;
?>
