# Veiledar: Validering og Sikring av PHP-skjema

Når ein jobbar med skjema, er den gylne regelen: **«Aldri stol på brukaren!»** (Never trust user input). Brukarar kan skrive feil, eller hackerar kan prøve å sende skadeleg kode.

Derfor må vi gjere to ting:
1.  **Sanitisering (Vasking):** Fjerne unødvendige teikn og gjere koden trygg (hindre XSS).
2.  **Validering (Sjekking):** Sjekke om dataene er i riktig format (f.eks. at ein e-post faktisk ser ut som ein e-post).

---

## 1. Vaskefunksjonen (Sanitisering)

Før vi sjekkar om dataene er rette, må vi vaske dei. Det er lurt å lage ein eigen funksjon for dette som gjer tre ting:

* `trim()`: Fjernar mellomrom før og etter teksten.
* `stripslashes()`: Fjernar backslashes `\` (kan øydelegge koden).
* `htmlspecialchars()`: Gjer om spesialteikn som `<` og `>` til HTML-koder. Dette stoppar hackerar frå å køyre JavaScript på sida di (XSS-angrep).

```php
function vask_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
```
## 2. Validering med filter_var og RegEx

* PHP har innebygde verktøy for å sjekke om data er gyldige.

* Sjekke e-post: filter_var($epost, FILTER_VALIDATE_EMAIL)

* Sjekke heiltal: filter_var($alder, FILTER_VALIDATE_INT)

* Sjekke URL: filter_var($nettside, FILTER_VALIDATE_URL)

* Sjekke namn/tekst: Her brukar vi ofte "Regular Expressions" (RegEx) for å tillate berre bokstavar (sjå døme under).
---

```php

<?php
// Definer variablar og set dei til tomme strengar
$namn = $epost = $alder = "";
$namnFeil = $epostFeil = $alderFeil = "";
$suksess = false;

// Funksjon for å vaske input
function vask_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Sjekk om skjemaet er sendt (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- 1. VALIDERE NAMN ---
    if (empty($_POST["namn"])) {
        $namnFeil = "Namn er påkravd.";
    } else {
        $namn = vask_input($_POST["namn"]);
        // Sjekk om namnet berre inneheld bokstavar og mellomrom (RegEx)
        if (!preg_match("/^[a-zA-Z-' æøåÆØÅ]*$/", $namn)) {
            $namnFeil = "Kun bokstavar og mellomrom er tillate.";
        }
    }

    // --- 2. VALIDERE E-POST ---
    if (empty($_POST["epost"])) {
        $epostFeil = "E-post er påkravd.";
    } else {
        $epost = vask_input($_POST["epost"]);
        // Sjekk om e-postadressa er gyldig
        if (!filter_var($epost, FILTER_VALIDATE_EMAIL)) {
            $epostFeil = "Ugyldig e-postformat.";
        }
    }

    // --- 3. VALIDERE ALDER (Valfritt felt, men må vere tal om det er fylt ut) ---
    if (empty($_POST["alder"])) {
        $alder = ""; // Det er lov å la vere å fylle ut
    } else {
        $alder = vask_input($_POST["alder"]);
        // Sjekk at det er eit heiltal
        if (!filter_var($alder, FILTER_VALIDATE_INT)) {
            $alderFeil = "Alder må vere eit heiltal.";
        } elseif ($alder < 18) {
             // Ekstra sjekk: Er brukaren myndig?
             $alderFeil = "Du må vere over 18 år.";
        }
    }

    // Dersom vi ikkje har nokon feilmeldinger, er alt OK!
    if(empty($namnFeil) && empty($epostFeil) && empty($alderFeil)) {
        $suksess = true;
    }
}
?>

<!DOCTYPE html>
<html lang="nn">
<head>
    <meta charset="UTF-8">
    <title>Sikker Validering</title>
    <style>
        .error {color: #FF0000;}
        .suksess-boks {background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px;}
        input {display: block; margin-bottom: 5px; padding: 5px;}
    </style>
</head>
<body>

    <h2>Registreringsskjema</h2>

    <?php if($suksess): ?>
        <div class="suksess-boks">
            Takk! Registreringa er motteken for <strong><?php echo $namn; ?></strong>.
        </div>
    <?php endif; ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
        
        <label>Namn:</label>
        <input type="text" name="namn" value="<?php echo $namn;?>">
        <span class="error"><?php echo $namnFeil;?></span>
        
        <br>

        <label>E-post:</label>
        <input type="text" name="epost" value="<?php echo $epost;?>">
        <span class="error"><?php echo $epostFeil;?></span>
        
        <br>

        <label>Alder (valfritt):</label>
        <input type="text" name="alder" value="<?php echo $alder;?>">
        <span class="error"><?php echo $alderFeil;?></span>

        <br>
        <button type="submit">Send inn</button>
    </form>

</body>
</html>

```

## 4. Oppsummering: Dei viktigaste sjekkane
1. Er feltet tomt? Bruk empty($_POST['felt']) for å sjekke om brukaren har gløymt å fylle ut noko som er påkravd.

2. Er formatet rett?

   * For e-post: filter_var($epost, FILTER_VALIDATE_EMAIL)

   * For tal: filter_var($tall, FILTER_VALIDATE_INT)

   * Hugs brukaren si oppleving (UX): I HTML-koden over ser du value="<?php echo $namn;?>". Dette er viktig! Dersom brukaren skriv feil e-post, men rett namn, skal ikkje namnet forsvinne når sida lastar på nytt. Vi skriv verdien tilbake i feltet.

3. Sikkerheit i ``` php <form action>``` : Legg merke til ``` action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"```. Dersom du berre skriv ```$_SERVER["PHP_SELF"]``` utan htmlspecialchars, kan ein hacker legge inn skadeleg kode i URL-en som vert køyrt på sida di.