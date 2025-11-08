# Notizen-App

Eine simple Notizen-App, die ich mit Laravel und Vue 3 gebaut habe. Perfekt für den Alltag – egal ob für schnelle Gedanken, Todo-Listen oder wichtige Aufgaben.

## Was kann die App?

### Die Basics
- Notizen erstellen, bearbeiten und löschen – wie man's erwartet
- Jede Notiz hat einen Titel, Inhalt und zeigt, wann sie erstellt wurde
- Notizen können als erledigt abgehakt werden (speichert auch den Zeitpunkt)
- Wichtige Notizen lassen sich markieren
- Sieht auf'm Handy genauso gut aus wie am Desktop

### Ein paar coole Extras
- **Suche**: Du kannst nach Titel oder Inhalt suchen
- **Filter**: Zeig nur offene, erledigte oder wichtige Notizen
- **Sortierung**: Nach Datum, Titel oder Wichtigkeit sortieren
- **Todo-Listen**: Die Checkboxen machen die App perfekt für Aufgabenlisten
- **Toast-Benachrichtigungen**: Du bekommst immer Feedback, was gerade passiert ist
- **Auto-Scroll & Highlight**: Neue oder bearbeitete Notizen werden kurz hervorgehoben
- **SPA-Architektur**: Die App lädt nur einmal, danach läuft alles flüssig über die API
- **Tests**: 13 Feature-Tests sorgen dafür, dass alles funktioniert

## Technischer Stack

### Backend
- **Laravel 12** – aktuellste Version
- **PHP 8.3+**
- **MariaDB** (wenn du DDEV nutzt) oder **SQLite** (ohne DDEV)
- **RESTful API** mit JSON

### Frontend
- **Vue 3** mit Composition API
- **Vite** – super schneller Build-Tool
- **Tailwind CSS 4** für das Design
- **Axios** für die API-Calls

### Entwicklungsumgebung
- **DDEV** – optional, aber ich empfehle es für die lokale Entwicklung
- **Docker** – brauchst du nur, wenn du DDEV nutzen willst

### Testing
- **PHPUnit** für automatisierte Tests
- 13 API-Tests, die alles abdecken

## Was brauchst du?

### Option 1: Mit DDEV (empfohlen)
- **DDEV** ([Installationsanleitung](https://ddev.readthedocs.io/en/stable/))
- **Docker Desktop** (braucht DDEV)
- **Git**

### Option 2: Ohne DDEV
- **PHP 8.3** oder neuer
- **Composer**
- **Node.js 18+** und npm
- **MySQL/MariaDB** oder SQLite
- **Git**

## Installation

Du hast zwei Möglichkeiten – mit oder ohne DDEV. Ich empfehle DDEV, weil's einfacher ist.

---

## Installation mit DDEV (Option 1)

### 1. Repo klonen
```bash
git clone <repository-url>
cd notizen-app
```

### 2. DDEV starten
```bash
ddev start
```

DDEV richtet automatisch alles ein:
- PHP 8.3
- MariaDB Datenbank
- Webserver (NGINX)
- Node.js und npm

### 3. Dependencies installieren

Backend:
```bash
ddev composer install
```

Frontend:
```bash
ddev npm install
```

### 4. App-Key generieren
```bash
ddev artisan key:generate
```

Die `.env` Datei wird von DDEV automatisch konfiguriert.

### 5. Datenbank aufsetzen
```bash
ddev artisan migrate:fresh --seed
```

Das erstellt die Tabellen und fügt 25 Test-Notizen ein (15 vordefinierte + 10 zufällige).

### 6. Frontend bauen
```bash
ddev npm run build
```

### 7. App öffnen
```bash
ddev launch
```

Die App läuft jetzt unter: `https://notizen-app.ddev.site`

---

## Installation ohne DDEV (Option 2)

### 1. Repo klonen
```bash
git clone <repository-url>
cd notizen-app
```

### 2. Backend aufsetzen

Dependencies installieren:
```bash
composer install
```

Umgebung konfigurieren:
```bash
cp .env.example .env
php artisan key:generate
```

#### Datenbank einrichten

**SQLite (am einfachsten):**

Die `.env.example` ist schon für SQLite konfiguriert. Du musst nur die Datei erstellen:

```bash
touch database/database.sqlite
```

**MySQL/MariaDB:**

Wenn du lieber MySQL/MariaDB nutzen willst, pass die `.env` an:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=notizen_app
DB_USERNAME=root
DB_PASSWORD=
```

Und erstell die Datenbank:
```bash
mysql -u root -p
CREATE DATABASE notizen_app;
exit
```

Jetzt die Tabellen und Test-Daten einfügen:
```bash
php artisan migrate:fresh --seed
```

### 3. Frontend einrichten

Dependencies installieren:
```bash
npm install
```

Assets bauen:
```bash
npm run build
```

### 4. App starten

Backend-Server starten:
```bash
php artisan serve
```

Die App läuft dann unter: `http://localhost:8000`

**Optional:** Wenn du am Frontend entwickeln willst, kannst du in einem zweiten Terminal den Dev-Server starten:
```bash
npm run dev
```

---

## App starten (nach der Installation)

### Mit DDEV

Nach der Installation ist die App schon verfügbar:

```bash
ddev launch
```

Das öffnet die App im Browser: `https://notizen-app.ddev.site`

**Frontend im Dev-Modus (optional):**
```bash
ddev npm run dev
```

**Die wichtigsten DDEV-Befehle:**
```bash
ddev start              # Umgebung starten
ddev stop               # Umgebung stoppen
ddev restart            # Umgebung neustarten
ddev ssh                # In den Container einloggen
ddev describe           # Infos über das Projekt
ddev logs               # Logs anzeigen
```

**Hinweis:** Mit `ddev ssh` öffnest du eine Shell **im** Docker-Container. Alles, was du da ausführst, läuft im Container. Mit `exit` kommst du wieder raus.

---

### Ohne DDEV

**Backend-Server:**
```bash
php artisan serve
```
Die App läuft unter: `http://localhost:8000`

**Frontend-Dev-Server (optional für Hot Reload):**
In einem zweiten Terminal:
```bash
npm run dev
```

Dann einfach `http://localhost:8000` im Browser öffnen.

## Tests laufen lassen

### Mit DDEV

Alle Tests:
```bash
ddev artisan test
```

Nur die Notizen-API-Tests:
```bash
ddev artisan test --filter=NoteApiTest
```

Mit mehr Details:
```bash
ddev artisan test --verbose
```

### Ohne DDEV

Alle Tests:
```bash
php artisan test
```

Nur die Notizen-API-Tests:
```bash
php artisan test --filter=NoteApiTest
```

Mit mehr Details:
```bash
php artisan test --verbose
```

## API-Doku

### Basis-URL

Mit DDEV:
```
https://notizen-app.ddev.site/api
```

Ohne DDEV:
```
http://localhost:8000/api
```

### Die Endpoints

#### Alle Notizen holen
```http
GET /api/notes
```

**Du kannst folgende Parameter anhängen:**
- `search` – Suche nach Titel oder Inhalt
- `is_important` – Zeig nur wichtige (`true`/`1`) oder unwichtige (`false`/`0`) Notizen
- `sort_by` – Sortieren nach `created_at`, `title` oder `is_important`
- `sort_order` – `asc` für aufsteigend, `desc` für absteigend

**Beispiel:**
```http
GET /api/notes?search=Laravel&is_important=1&sort_by=created_at&sort_order=desc
```

#### Eine einzelne Notiz holen
```http
GET /api/notes/{id}
```

#### Neue Notiz erstellen
```http
POST /api/notes
Content-Type: application/json

{
  "title": "Meine Notiz",
  "content": "Inhalt der Notiz",
  "is_important": false
}
```

#### Notiz bearbeiten
```http
PUT /api/notes/{id}
Content-Type: application/json

{
  "title": "Aktualisierter Titel",
  "content": "Aktualisierter Inhalt",
  "is_important": true
}
```

#### Notiz löschen
```http
DELETE /api/notes/{id}
```

#### Notiz abhaken/aufhaken
```http
PATCH /api/notes/{id}/toggle-completed
```

Dieser Endpoint wechselt zwischen erledigt und offen. Du brauchst keinen Request-Body.

## Projektstruktur

```
notizen-app/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           └── NoteController.php    # API Controller
│   └── Models/
│       └── Note.php                      # Note Model
├── database/
│   ├── factories/
│   │   └── NoteFactory.php               # Test-Daten Factory
│   ├── migrations/
│   │   └── *_create_notes_table.php      # Datenbank-Migration
│   └── seeders/
│       ├── DatabaseSeeder.php            # Haupt-Seeder
│       └── NoteSeeder.php                # Notizen-Seeder
├── resources/
│   ├── js/
│   │   ├── components/
│   │   │   ├── NoteApp.vue               # Haupt-Komponente
│   │   │   ├── NoteCard.vue              # Notiz-Karte
│   │   │   ├── NoteModal.vue             # Modal für Erstellen/Bearbeiten
│   │   │   └── ToastNotification.vue     # Toast-Benachrichtigungen
│   │   ├── App.vue                       # Root Vue-Komponente
│   │   └── app.js                        # Vue-App Einstiegspunkt
│   ├── css/
│   │   └── app.css                       # Tailwind CSS
│   └── views/
│       └── app.blade.php                 # Haupt-Layout
├── routes/
│   ├── api.php                           # API-Routes
│   └── web.php                           # Web-Routes
├── tests/
│   └── Feature/
│       └── NoteApiTest.php               # API Feature-Tests
├── .ddev/                                # DDEV-Konfiguration
├── .env.example                          # Umgebungsvariablen-Vorlage
├── composer.json                         # PHP Dependencies
├── package.json                          # NPM Dependencies
├── vite.config.js                        # Vite-Konfiguration
└── README.md                             # Diese Datei
```

## Entwicklungs-Notizen

### Laravel-Konventionen
- Die Controller halten sich an Laravel-Standards
- RESTful Resource Controller für die API
- Model nutzt `$fillable` und `$casts`
- Validierung passiert in den Controller-Methoden

### Vue Best Practices
- Durchgängig Composition API
- Komponenten sind modular aufgebaut
- Props und Emits sind sauber typisiert
- Responsives Design mit Tailwind CSS

### Code-Qualität
- Saubere Trennung zwischen Backend und Frontend
- Die API folgt RESTful-Prinzipien
- Laravel- und Vue-Best-Practices umgesetzt
- Keine zusätzlichen Pakete außer dem Laravel-Standard

## Die Features im Detail

### Suche
Die Suche durchsucht Titel und Inhalt gleichzeitig – in Echtzeit.

### Filter

**Nach Status:**
- **Alle** – Zeigt alles
- **Offen** – Nur nicht erledigte Notizen
- **Erledigt** – Nur abgehakte Notizen

**Nach Wichtigkeit:**
- **Alle** – Zeigt alles
- **Wichtig** – Nur die wichtigen Notizen

### Sortierung
- **Neueste zuerst** – Nach Erstellungsdatum absteigend
- **Älteste zuerst** – Nach Erstellungsdatum aufsteigend
- **Titel A-Z** – Alphabetisch
- **Titel Z-A** – Alphabetisch rückwärts
- **Wichtigkeit** – Wichtige zuerst

### Responsives Design
- Funktioniert auf Handy, Tablet und Desktop
- Das Grid passt sich automatisch an
- Touch-friendly auf Mobilgeräten

### Task-Management
- **Checkbox** – Jede Notiz kann abgehakt werden
- **Visuelle Kennzeichnung** – Erledigte Notizen sind durchgestrichen und ausgegraut
- **Timestamp** – Die App merkt sich, wann du was erledigt hast
- **Toggle** – Du kannst den Status jederzeit ändern
- **Use-Cases** – Perfekt für Todo-Listen, Einkaufslisten oder Aufgaben-Tracking

## Wenn's nicht läuft

### Mit DDEV

**DDEV startet nicht:**
```bash
# Prüf erst mal, ob Docker läuft
docker ps

# DDEV neustarten
ddev restart

# Wenn nix hilft, alles neu aufsetzen
ddev delete -O
ddev config
ddev start
```

**Datenbank-Probleme:**
```bash
# Datenbank neu aufsetzen
ddev artisan migrate:fresh --seed
```

**Frontend lädt nicht richtig:**
```bash
# Assets neu bauen
ddev npm run build

# Wenn's immer noch nicht geht, node_modules neu installieren
ddev ssh
rm -rf node_modules package-lock.json
exit
ddev npm install
ddev npm run build
```

**Cache-Probleme:**
```bash
# Alle Laravel-Caches leeren
ddev artisan config:clear
ddev artisan cache:clear
ddev artisan route:clear
ddev artisan view:clear
```

**Port-Konflikte:**
Wenn DDEV meckert, dass die Ports schon belegt sind:
```bash
ddev stop
# Bearbeite .ddev/config.yaml und ändere die Ports
ddev start
```

---

### Ohne DDEV

**Port 8000 ist schon belegt:**
```bash
php artisan serve --port=8001
```

**Vite macht Probleme:**
```bash
# node_modules neu installieren
rm -rf node_modules package-lock.json
npm install
npm run dev
```

**Datenbank-Probleme:**
```bash
# Datenbank neu aufsetzen
php artisan migrate:fresh --seed
```

**Cache-Probleme:**
```bash
# Laravel-Caches leeren
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

**Composer-Fehler:**
```bash
# Composer-Cache leeren und neu installieren
composer clear-cache
composer install
```

## Datenbank ansehen

### Mit DDEV

**Via MySQL-CLI:**
```bash
# MySQL-CLI öffnen
ddev mysql

# Datenbank-Infos anzeigen
ddev describe
```

**Via Adminer (Web-Interface):**
```bash
ddev launch -a
```

**Zugangsdaten für Adminer:**
- Server: `db`
- Benutzername: `db`
- Passwort: `db`
- Datenbank: `db`

---

### Ohne DDEV

**SQLite:**
```bash
# Datenbank öffnen
sqlite3 database/database.sqlite

# Tabellen anzeigen
.tables

# Query ausführen
SELECT * FROM notes;

# Raus
.quit
```

**MySQL/MariaDB:**
```bash
# MySQL-CLI öffnen
mysql -u root -p

# Datenbank auswählen
USE notizen_app;

# Tabellen anzeigen
SHOW TABLES;

# Query ausführen
SELECT * FROM notes;
```

## Nützliche DDEV-Befehle

```bash
# Projekt-Status
ddev describe

# In den Container einloggen
ddev ssh

# Composer-Befehle
ddev composer <befehl>

# Artisan-Befehle
ddev artisan <befehl>

# NPM-Befehle
ddev npm <befehl>

# Logs anschauen
ddev logs

# Backup erstellen
ddev snapshot

# Backup wiederherstellen
ddev snapshot restore
```

### Was macht `ddev ssh`?

Mit `ddev ssh` loggst du dich direkt in den Container ein. Du landest dann in `/var/www/html` innerhalb des Containers.

**Beispiel:**
```bash
$ ddev ssh
web:/var/www/html$ ls              # Dateien im Container
web:/var/www/html$ php artisan -V  # Laravel-Version (ohne ddev davor)
web:/var/www/html$ npm -v          # NPM-Version
web:/var/www/html$ exit            # Container verlassen
$
```

**Wann brauchst du das?**
- Debugging direkt im Container
- Direkte Datei-Operationen
- Manuelle Paket-Installation ohne `ddev`-Präfix
- Umgebungsvariablen im Container checken

**Tipp:** Normalerweise brauchst du `ddev ssh` nicht – du kannst fast alles mit `ddev artisan`, `ddev composer`, etc. von außen machen.

## Deployment

Für Production solltest du nicht DDEV nutzen. Stattdessen:

1. Normaler Webserver (NGINX/Apache) mit PHP-FPM
2. MySQL/MariaDB Datenbank
3. Laravel-Queue-Worker für Background-Jobs (falls nötig)
4. Vite-Assets im Production-Modus bauen

Mehr Details in der Laravel-Doku: [Deployment](https://laravel.com/docs/deployment)

