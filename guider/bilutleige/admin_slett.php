<?php
session_start();
include 'db.php';

// Sjekk tilgang!
if (!isset($_SESSION['rolle']) || $_SESSION['rolle'] != 'admin') {
    header("Location: index.php"); exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prøver å slette bilen
    $stmt = $kopling->prepare("DELETE FROM bilar WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin.php"); // Suksess
    } else {
        // Dette skjer ofte viss bilen allereie har bestillingar (Foreign Key error)
        echo "Kunne ikkje slette bilen. Kanskje den har aktive bestillingar?";
        echo "<br><a href='admin.php'>Tilbake</a>";
    }
}
?>