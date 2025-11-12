<?php
    // Hentar inn fila "db.php" som inneheld oppsettet for databasekoplinga.
    // Dette gjer at vi kan bruke same tilkopling i fleire PHP-filer utan å gjenta koden.
    require_once "db.php"; 

    // Opprettar ein variabel for databasekoplinga slik at koden under vert enklare å lese.
    $tilkobling = $conn;

    // SQL-spørsmål som hentar ut data frå to tabellar: "sitat" og "person".
    // Den koplar saman tabellane ved å bruke feltet "personid" i tabellen "sitat"
    // som peikar til "id" i tabellen "person".
    // Resultatet blir ei liste der kvart sitat får med seg kven som sa det (namn og periode).
    $sql = "SELECT sitat.id, sitat.tekst, person.namn, person.periode
        FROM sitat
        JOIN person ON sitat.personid = person.id";

    // Køyrer SQL-spørsmålet mot databasen og lagrar resultatet i variabelen $datasett.
    $datasett = $tilkobling->query($sql);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Sitater</title>
        <meta charset="utf-8" />
        <!-- Knyter til ei ekstern stilfil (CSS) for å formatere utsjånaden til tabellen -->
        <link rel ="stylesheet" type="text/css" href="stil.css">
    </head>

    <body>
        <!-- Startar tabell som skal vise alle sitata frå databasen -->
        <table>
            <tr>
                <!-- Tabelloverskrifter -->
                <th>ID</th>
                <th>Tekst</th>
                <th>Navn</th>
                <th>Periode</th>
            </tr>

            <!-- Startar ei løkke som går gjennom kvar rad i resultatet frå databasen -->
            <?php while($rad = mysqli_fetch_array($datasett)) { ?>
            <tr>
                <!-- Viser kvar kolonne frå databasen i kvar sin tabellcelle -->
                <td><?php echo $rad["id"]; ?></td>
                <td><?php echo $rad["tekst"]; ?></td>
                <td><?php echo $rad["namn"]; ?></td>
                <td><?php echo $rad["periode"]; ?></td>
            </tr>
            <?php } ?> <!-- Avsluttar while-løkka -->
        </table>
    </body>
</html>
