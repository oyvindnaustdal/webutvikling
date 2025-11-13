<?php
     require_once "db.php"; // Brukar same databasekopling som resten av systemet

    // Koble til databasen
    $tilkobling = $conn;
   
    // Kontroller tilkobling
    if (!$tilkobling) {
        die("Tilkobling feila: " . mysqli_connect_error());
    }

    //  Hent data frå databasen
    $sql = "SELECT sitat.id, sitat.tekst, person.namn, person.periode
        FROM  sitat
        JOIN person ON sitat.personid = person.id";
    $datasett = $tilkobling->query($sql);
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Sitater region</title>
        <meta charset="utf-8" />
        <link rel ="stylesheet" type="text/css" href="stil.css">
    </head>
    <body>
        <?php while($rad = mysqli_fetch_array($datasett)) { ?>
        <p> 
            <strong>Sitatet:</strong><em>&quot; <?php echo $rad["tekst"]; 
                ?>&quot;</em><br />
            <strong>Blei skrevet av:</strong> <?php echo $rad["namn"]; ?><br />
            <strong>Som levde i perioden:</strong> <?php echo $rad["periode"]; ?>
        </p>
        <?php } ?>
    
    </body>
</html>