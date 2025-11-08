<?php

// Database konfigurasjon
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sitatregister";

$conn = new mysqli($servername, $username, $password, $dbname);

// Sjekk tilkopling
if ($conn->connect_error) {
    die("Feil ved tilkopling: " . $conn->connect_error);
}
?>
