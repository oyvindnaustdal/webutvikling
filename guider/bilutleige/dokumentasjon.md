# Dokumentasjon for Bilutleigesystem

## 1. Om systemet
Dette er eit komplett, webbasert system for bilutleige. Systemet lèt kundar registrere seg, logge inn og bestille bilar. Det har også eit administrasjonspanel der administrator kan legge til, endre og slette bilar (CRUD).

### Teknologiar
* **Språk:** PHP 8+
* **Database:** MySQL / MariaDB
* **Frontend:** HTML5, CSS3
* **Server:** Apache (via XAMPP/WAMP)

---

## 2. Installasjon og Oppsett

### Steg 1: Klargjering av filer
1.  Lag ei mappe i web-rota di (f.eks. `htdocs/bilutleige`).
2.  Legg alle PHP- og CSS-filene i denne mappa.

### Steg 2: Database
1.  Gå til **MySQL Workbench**
2.  Lag ein ny database med namnet `bilutleige`.
3.  Logg inn som adminstator og køyr(query) følgjande kode for å opprette tabellar og testdata:

```sql
CREATE TABLE brukarar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    epost VARCHAR(255) NOT NULL UNIQUE,
    passord VARCHAR(255) NOT NULL,
    namn VARCHAR(255) NOT NULL,
    rolle VARCHAR(20) DEFAULT 'kunde'
);

CREATE TABLE bilar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    merke VARCHAR(100) NOT NULL,
    modell VARCHAR(100) NOT NULL,
    pris INT NOT NULL,
    bilete VARCHAR(255)
);

CREATE TABLE bestillingar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brukar_id INT NOT NULL,
    bil_id INT NOT NULL,
    fra_dato DATE NOT NULL,
    til_dato DATE NOT NULL,
    FOREIGN KEY (brukar_id) REFERENCES brukarar(id),
    FOREIGN KEY (bil_id) REFERENCES bilar(id)
);
```

### Datamodell:
### ![Datamodell](bilutleige.png)

### Legg inn testdata
```sql
INSERT INTO bilar (merke, modell, pris, bilete) VALUES 
('Toyota', 'Yaris', 500, 'yaris.jpg'),
('Volvo', 'XC90', 1200, 'volvo.jpg'),
('Tesla', 'Model 3', 900, 'tesla.jpg');
```
MERK: For å lage ein admin, registrer ein brukar på nettsida først, 
og køyr deretter denne linja i SQL (byt ut eposten):
```sql
UPDATE brukarar SET rolle = 'admin' WHERE epost = 'din@epost.no';
```
---
### Steg 3: Opprett Admin-brukar

1.  Gå til nettsida og registrer ein ny brukar (f.eks. `admin@bil.no`).
2.  Gå inn i databasen (phpMyAdmin) -> tabellen `brukarar`.
3.  Endre feltet `rolle` frå `'kunde'` til `'admin'` på brukaren du nettopp laga.

---

## 3. Filoversikt

### Kjernen
* **`db.php`**: Handterer tilkobling til databasen. Inkluderast i alle filer.
* **`style.css`**: Stiler og utsjånad for heile nettsida.

### Brukarflyt (Frontend)
* **`index.php`**: Framside. Listar opp alle tilgjengelege bilar.
* **`registrer.php`**: Skjema for å opprette ny brukar. Hasher passord.
* **`logginn.php`**: Innlogging. Sjekkar e-post, passord og rolle.
* **`bestill.php`**: Bestillingsskjema. Sjekkar om bilen er ledig i valt periode (kollisjonssjekk).
* **`minside.php`**: Oversikt over kundens eigne bestillingar (viser data via SQL JOIN).
* **`loggut.php`**: Avsluttar sesjonen (loggar ut).
* **`meny.php`**: Meny som kan brukast på fleire sider.

### Administratorflyt (Backend)
* **`admin.php`**: Hovudpanelet. Viser tabell over alle bilar med knappar for endring/sletting.
* **`admin_leggtil.php`**: Skjema for å registrere ny bil (Create).
* **`admin_rediger.php`**: Skjema for å endre eksisterande bil (Update).
* **`admin_slett.php`**: Slettar ein bil frå databasen (Delete).

---

## 4. Database-struktur (ER)

Systemet bruker ein relasjonsdatabase med følgjande samanhengar:

* **1 Brukar** kan ha **mange Bestillingar** (1:N).
* **1 Bil** kan ha **mange Bestillingar** (1:N).
* Tabellen `bestillingar` knyter saman `brukarar` og `bilar` via framandnøklar (`brukar_id` og `bil_id`).



---

## 5. Sikkerheitstiltak

Følgjande mekanismar er implementert for å sikre applikasjonen:

### SQL Injection
Vi brukar **Prepared Statements** (`$kopling->prepare()`) og `bind_param()` overalt der brukardata skal inn i databasen. Dette skil SQL-kode frå data.

### XSS (Cross-Site Scripting)
All utskrift av data frå databasen til HTML blir køyrd gjennom `htmlspecialchars()`. Dette gjer om teikn som `<` og `>` til trygg tekst, slik at ingen kan køyre vondsinna JavaScript.

### Passordlagring
Passord blir aldri lagra i klartekst. Vi brukar `password_hash()` ved registrering og `password_verify()` ved innlogging.

### Tilgangskontroll (Autorisasjon)
* Alle sider som krev innlogging sjekkar `if(!isset($_SESSION['bruker_id']))`.
* Adminsider har ein ekstra sjekk: `if($_SESSION['rolle'] != 'admin')`. Dette hindrar vanlege kundar i å få tilgang til admin-panelet sjølv om dei gjettar URL-en.

### Validering av bestilling
Systemet sjekkar i SQL om ein bil allereie er utleigd i den valde perioden før ei ny bestilling blir lagra.

---

## 6. Vidareutvikling (Forslag)

* Legg til funksjonalitet for å laste opp bilete av bilar (file upload) i staden for berre tekstfelt.
* Moglegheit for admin til å slette bestillingar.
* Filtrering av bilar på framsida (f.eks. etter pris eller merke).











