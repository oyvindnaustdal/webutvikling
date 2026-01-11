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
