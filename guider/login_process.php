<?php
session_start();
require_once "db.php";

$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";

if (empty($username) || empty($password)) {
    $_SESSION["error"] = "Du må skrive inn både brukarnamn og passord.";
    header("Location: login.php");
    exit;
}

$sql = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row["password"])) {
        $_SESSION["logged_in"] = true;
        $_SESSION["username"] = $row["username"];
        $_SESSION["role"] = $row["role"];
        header("Location: personadministrasjonNY.php");
        exit;
    } else {
        $_SESSION["error"] = "Feil passord. Prøv igjen.";
    }
} else {
    $_SESSION["error"] = "Dette brukarnamnet finst ikkje.";
}

$stmt->close();
$conn->close();

header("Location: login.php");
exit;
?>
