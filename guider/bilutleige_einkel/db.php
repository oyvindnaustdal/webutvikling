<?php
$host = "localhost";
$user = "root";
$pass = "root";
$db   = "bilutleige"; // Hugs at du må ha laga databasen først!

$kopling = new mysqli($host, $user, $pass, $db);

if ($kopling->connect_error) {
    die("Feil: " . $kopling->connect_error);
}
?>