<?php
// Innstillingar for database
$host = "localhost";
$user = "root";
$pass = "root"; // Standard passord i XAMPP er tomt
$db   = "bilutleige";

// Opprettar koblinga med objekt-orientert stil (mysqli)
$kopling = new mysqli($host, $user, $pass, $db);

// Sjekkar om vi fekk kontakt
if ($kopling->connect_error) {
    die("Kopling feila: " . $kopling->connect_error);
}

?>