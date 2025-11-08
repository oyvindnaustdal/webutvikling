<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "sitatregister";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Feil ved tilkopling: " . $conn->connect_error);
}
?>
