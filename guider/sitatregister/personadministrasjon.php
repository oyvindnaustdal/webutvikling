<?php
session_start();
require_once "db.php"; // Brukar same databasekopling som resten av systeme



// --- DATABASEKOPLING ---
$tilkobling = $conn; // Bruk same tilkopling frå db.php

// --- SLETTING AV PERSON ---
if (isset($_GET["slettID"])) {
    $stmt = $tilkobling->prepare("DELETE FROM person WHERE id = ?");
    $stmt->bind_param("i", $_GET["slettID"]);
    $stmt->execute();
    $stmt->close();
    header("Location: personadministrasjon.php"); // Oppfrisk sida etter sletting
    exit;
}

// --- HENT DATA FRÅ TABELLEN ---
$sql = "SELECT id, namn, periode FROM person";
$datasett = $tilkobling->query($sql);
?>
<!DOCTYPE html>
<html lang="nn">
<head>
    <meta charset="utf-8" />
    <title>Personadministrasjon</title>
    <link rel="stylesheet" href="stil.css" />
    
</head>
<body>

<div class="topbar">
    <h1>Personadministrasjon</h1>
    
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Namn</th>
        <th>Periode</th>
        <th>Handlingar</th>
    </tr>
    <?php while($rad = $datasett->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $rad["id"]; ?></td>
        <td><?php echo htmlspecialchars($rad["namn"]); ?></td>
        <td><?php echo htmlspecialchars($rad["periode"]); ?></td>
        <td>
            <a href="?slettID=<?php echo $rad["id"]; ?>" onclick="return confirm('Er du sikker på at du vil slette denne personen?');">Slett</a> |
            <a href="oppdaterperson.php?oppdaterID=<?php echo $rad['id']; ?>">Oppdater</a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
