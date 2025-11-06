# Notizen-App

Eine moderne Webanwendung zur Verwaltung von Notizen, gebaut mit Laravel und Vue 3.

## Features

### Grundfunktionen
- Notizen erstellen, bearbeiten und löschen
- Notizen anzeigen mit Titel, Inhalt und Erstellungsdatum
- Notizen als erledigt markieren (mit Timestamp)
- Wichtige Notizen markieren
- Responsives Design (Mobile & Desktop)

### Bonus-Features
- **Such-Funktion**: Notizen nach Titel oder Inhalt durchsuchen
- **Filter**: Notizen nach Status (Offen/Erledigt) und Wichtigkeit filtern
- **Sortierung**: Nach Datum, Titel oder Wichtigkeit sortieren
- **Task-Management**: Notizen als Todo-Items mit Checkbox nutzen
- **Toast-Benachrichtigungen**: Feedback für alle Aktionen
- **Auto-Scroll & Highlight**: Neue/bearbeitete Notizen werden hervorgehoben
- **SPA-Architektur**: Single Page Application mit Vue 3 und RESTful API
- **Tests**: Umfassende Feature-Tests für die API (13 Tests)

## Technologie-Stack

### Backend
- **Laravel 12** (aktuelle Version)
- **PHP 8.3+**
- **MariaDB** (mit DDEV) oder **SQLite** (ohne DDEV)
- **RESTful API** mit JSON-Responses

### Frontend
- **Vue 3** mit Composition API
- **Vite** als Build-Tool
- **Tailwind CSS 4** für modernes, responsives Design
- **Axios** für HTTP-Anfragen

### Development Environment
- **DDEV** (optional, empfohlen für lokale Entwicklung)
- **Docker** (optional, nur für DDEV benötigt)

### Testing
- **PHPUnit** für Feature-Tests
- 13 umfassende API-Tests (inkl. Toggle-Completed)

## Voraussetzungen

### Option 1: Mit DDEV (empfohlen für lokale Entwicklung)
- **DDEV** installiert ([Installation Guide](https://ddev.readthedocs.io/en/stable/))
- **Docker Desktop** (wird von DDEV benötigt)
- **Git**

### Option 2: Klassische Laravel-Installation
- **PHP 8.3** oder höher
- **Composer**
- **Node.js 18+** und npm
- **MySQL/MariaDB** oder SQLite
- **Git**

## Installation

Wählen Sie einen der beiden Installationswege:

---

## Installation mit DDEV (Option 1)

### 1. Repository klonen
```bash
git clone <repository-url>
cd notizen-app
```

### 2. DDEV-Umgebung starten
```bash
ddev start
```

DDEV konfiguriert automatisch:
- PHP 8.3
- MariaDB Datenbank
- Webserver (NGINX)
- Node.js und npm

### 3. Dependencies installieren

#### Backend-Dependencies
```bash
ddev composer install
```

#### Frontend-Dependencies
```bash
ddev npm install
```

### 4. Umgebung konfigurieren
```bash
ddev artisan key:generate
```

Die `.env` Datei wird von DDEV automatisch für MariaDB konfiguriert.

### 5. Datenbank-Migrationen und Seeding
```bash
ddev artisan migrate:fresh --seed
```

Dieser Befehl:
- Erstellt die Datenbank-Tabellen
- Füllt die Datenbank mit 25 Test-Notizen (15 vordefinierte + 10 Faker-generierte)

### 6. Frontend-Assets kompilieren
```bash
ddev npm run build
```

### 7. Anwendung öffnen
```bash
ddev launch
```

Die Anwendung ist verfügbar unter: `https://notizen-app.ddev.site`

---

## Installation ohne DDEV (Option 2)

### 1. Repository klonen
```bash
git clone <repository-url>
cd notizen-app
```

### 2. Backend einrichten

#### Dependencies installieren
```bash
composer install
```

#### Umgebung konfigurieren
```bash
cp .env.example .env
php artisan key:generate
```

#### Datenbank konfigurieren

**Für SQLite (einfach):**

Die `.env.example` ist bereits für SQLite konfiguriert. Erstellen Sie nur die Datenbank-Datei:

```bash
touch database/database.sqlite
```

**Für MySQL/MariaDB:**

Passen Sie die `.env` Datei an:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=notizen_app
DB_USERNAME=root
DB_PASSWORD=
```

Erstellen Sie die Datenbank:
```bash
mysql -u root -p
CREATE DATABASE notizen_app;
exit
```

#### Datenbank-Migrationen und Seeding
```bash
php artisan migrate:fresh --seed
```

### 3. Frontend einrichten

#### Dependencies installieren
```bash
npm install
```

#### Assets kompilieren
```bash
npm run build
```

### 4. Anwendung starten

#### Backend-Server starten (in einem Terminal)
```bash
php artisan serve
```

Die Anwendung ist verfügbar unter: `http://localhost:8000`

#### Frontend-Dev-Server (optional, für Hot Reload)
In einem zweiten Terminal:
```bash
npm run dev
```

---

## Anwendung starten (nach Installation)

### Mit DDEV

Die Anwendung ist nach der Installation automatisch verfügbar:

```bash
ddev launch
```

Dieser Befehl öffnet die Anwendung im Browser: `https://notizen-app.ddev.site`

**Frontend im Dev-Modus (optional):**
```bash
ddev npm run dev
```

**Wichtige DDEV-Befehle:**
```bash
ddev start              # Umgebung starten
ddev stop               # Umgebung stoppen
ddev restart            # Umgebung neustarten
ddev ssh                # In den Web-Container einloggen (Shell im Container)
ddev describe           # Projekt-Informationen anzeigen
ddev logs               # Logs anzeigen
```

**Wichtig:** `ddev ssh` öffnet eine Shell **im** Docker-Container. Alle Befehle, die Sie dann ausführen, laufen innerhalb des Containers. Um den Container wieder zu verlassen, verwenden Sie `exit`.

---

### Ohne DDEV (klassisches Laravel)

**Backend-Server starten:**
```bash
php artisan serve
```
Die API ist verfügbar unter: `http://localhost:8000`

**Frontend-Dev-Server (optional, für Hot Reload):**
In einem zweiten Terminal:
```bash
npm run dev
```

**Anwendung öffnen:**
Öffnen Sie Ihren Browser und navigieren Sie zu: `http://localhost:8000`

## Tests ausführen

### Mit DDEV

**Alle Tests ausführen:**
```bash
ddev artisan test
```

**Nur Notizen-API-Tests:**
```bash
ddev artisan test --filter=NoteApiTest
```

**Tests mit Details:**
```bash
ddev artisan test --verbose
```

### Ohne DDEV (klassisches Laravel)

**Alle Tests ausführen:**
```bash
php artisan test
```

**Nur Notizen-API-Tests:**
```bash
php artisan test --filter=NoteApiTest
```

**Tests mit Details:**
```bash
php artisan test --verbose
```

## API-Dokumentation

### Basis-URL

**Mit DDEV:**
```
https://notizen-app.ddev.site/api
```

**Ohne DDEV:**
```
http://localhost:8000/api
```

### Endpoints

#### Alle Notizen abrufen
```http
GET /api/notes
```

**Query-Parameter:**
- `search` (optional): Suche nach Titel oder Inhalt
- `is_important` (optional): Filter nach Wichtigkeit (`true`/`false`/`1`/`0`)
- `sort_by` (optional): Sortierfeld (`created_at`, `title`, `is_important`)
- `sort_order` (optional): Sortierrichtung (`asc`, `desc`)

**Beispiel:**
```http
GET /api/notes?search=Laravel&is_important=1&sort_by=created_at&sort_order=desc
```

#### Einzelne Notiz abrufen
```http
GET /api/notes/{id}
```

#### Notiz erstellen
```http
POST /api/notes
Content-Type: application/json

{
  "title": "Meine Notiz",
  "content": "Inhalt der Notiz",
  "is_important": false
}
```

#### Notiz aktualisieren
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

#### Notiz als erledigt markieren (Toggle)
```http
PATCH /api/notes/{id}/toggle-completed
```

**Hinweis:** Dieser Endpoint wechselt zwischen erledigt/unerledigt. Kein Request-Body erforderlich.

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

## Entwicklungs-Hinweise

### Laravel-Konventionen
- Alle Controller folgen den Laravel-Konventionen
- RESTful Resource Controller für API
- Model verwendet `$fillable` und `$casts`
- Validierung in Controller-Methoden

### Vue Best Practices
- Composition API wird durchgängig verwendet
- Komponenten sind modular und wiederverwendbar
- Props und Emits sind typisiert
- Responsive Design mit Tailwind CSS

### Code-Qualität
- Saubere Trennung von Backend und Frontend
- API folgt RESTful-Prinzipien
- Laravel und Vue Best Practices
- Keine externen Pakete außer Laravel-Standard

## Features im Detail

### Such-Funktion
Die Suchfunktion durchsucht sowohl Titel als auch Inhalt der Notizen in Echtzeit.

### Filter

**Status-Filter:**
- **Alle**: Zeigt alle Notizen (offen und erledigt)
- **Offen**: Zeigt nur offene Notizen
- **Erledigt**: Zeigt nur erledigte Notizen

**Wichtigkeits-Filter:**
- **Alle**: Zeigt alle Notizen
- **Wichtig**: Zeigt nur wichtige Notizen

### Sortierung
- **Neueste zuerst**: Nach Erstellungsdatum absteigend
- **Älteste zuerst**: Nach Erstellungsdatum aufsteigend
- **Titel A-Z**: Alphabetisch aufsteigend
- **Titel Z-A**: Alphabetisch absteigend
- **Wichtigkeit**: Wichtige Notizen zuerst

### Responsives Design
- Optimiert für Mobile, Tablet und Desktop
- Grid-Layout passt sich an Bildschirmgröße an
- Touch-friendly Interface auf Mobile-Geräten

### Task-Management mit Completed-Status
- **Checkbox:** Jede Notiz hat eine Checkbox zum Markieren
- **Visuelle Kennzeichnung:** Erledigte Notizen sind durchgestrichen und leicht ausgegraut
- **Timestamp:** System speichert WANN eine Notiz erledigt wurde
- **Toggle:** Erledigt-Status kann jederzeit gewechselt werden
- **Use-Cases:** Ideal für Todo-Listen, Einkaufslisten, Aufgaben-Tracking

## Fehlerbehebung

### Mit DDEV

**DDEV startet nicht:**
```bash
# Docker prüfen
docker ps

# DDEV neustarten
ddev restart

# DDEV-Konfiguration neu erstellen
ddev delete -O
ddev config
ddev start
```

**Datenbank-Probleme:**
```bash
# Datenbank zurücksetzen und neu befüllen
ddev artisan migrate:fresh --seed
```

**Frontend-Assets werden nicht geladen:**
```bash
# Assets neu kompilieren
ddev npm run build

# Bei Problemen: node_modules neu installieren
ddev ssh
rm -rf node_modules package-lock.json
exit
ddev npm install
ddev npm run build
```

**Cache-Probleme:**
```bash
# Laravel-Caches leeren
ddev artisan config:clear
ddev artisan cache:clear
ddev artisan route:clear
ddev artisan view:clear
```

**Port-Konflikte:**
Falls DDEV Port-Konflikte meldet:
```bash
# Ports in .ddev/config.yaml anpassen
ddev stop
# config.yaml bearbeiten
ddev start
```

---

### Ohne DDEV (klassisches Laravel)

**Port bereits in Verwendung:**
Falls Port 8000 bereits verwendet wird:
```bash
php artisan serve --port=8001
```

**Vite-Fehler:**
Falls der Vite-Server nicht startet:
```bash
# Node-Modules neu installieren
rm -rf node_modules package-lock.json
npm install
npm run dev
```

**Datenbank-Probleme:**
```bash
# Datenbank zurücksetzen
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
# Composer-Cache leeren
composer clear-cache
composer install
```

## Datenbank-Zugriff

### Mit DDEV

**Via MySQL-CLI:**
```bash
# MySQL-CLI öffnen
ddev mysql

# Datenbank-Informationen anzeigen
ddev describe
```

**Via Adminer (Web-Interface):**
```bash
ddev launch -a
```

**Zugangsdaten:**
- Server: `db`
- Benutzername: `db`
- Passwort: `db`
- Datenbank: `db`

---

### Ohne DDEV

**SQLite:**
```bash
# SQLite-Datenbank öffnen
sqlite3 database/database.sqlite

# Tabellen anzeigen
.tables

# Query ausführen
SELECT * FROM notes;

# Verlassen
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

## Nützliche DDEV-Befehle (nur für DDEV-Nutzer)

```bash
# Projekt-Status anzeigen
ddev describe

# In Container einloggen (öffnet Shell IM Container)
ddev ssh

# Composer-Befehle ausführen
ddev composer <befehl>

# Artisan-Befehle ausführen
ddev artisan <befehl>

# NPM-Befehle ausführen
ddev npm <befehl>

# Logs anzeigen
ddev logs

# Snapshots erstellen (Backup)
ddev snapshot

# Snapshot wiederherstellen
ddev snapshot restore
```

### Container-Zugriff mit `ddev ssh` (nur bei DDEV)

Mit `ddev ssh` loggen Sie sich direkt in den Web-Container ein. Sie befinden sich dann im Projektverzeichnis `/var/www/html` innerhalb des Containers.

**Beispiel:**
```bash
$ ddev ssh
web:/var/www/html$ ls              # Dateien im Container anzeigen
web:/var/www/html$ php artisan -V  # Laravel-Version prüfen (ohne ddev-Präfix)
web:/var/www/html$ npm -v          # NPM-Version prüfen
web:/var/www/html$ exit            # Container verlassen
$
```

**Wann benötigt man `ddev ssh`?**
- Debugging im Container-Kontext
- Direkte Datei-Operationen
- Manuelle Paket-Installation ohne `ddev`-Präfix
- Umgebungsvariablen im Container prüfen

**Hinweis:** Normalerweise ist `ddev ssh` nicht nötig, da Sie alle Befehle mit `ddev artisan`, `ddev composer`, etc. von außen ausführen können.

## Deployment

Für Production-Deployment sollte die Anwendung nicht mit DDEV betrieben werden. Stattdessen:

1. Standard-Webserver (NGINX/Apache) mit PHP-FPM
2. MySQL/MariaDB Datenbank
3. Laravel-Queue-Worker für Background-Jobs (falls benötigt)
4. Vite-Assets im Production-Modus kompilieren

Details siehe Laravel-Dokumentation: [Deployment](https://laravel.com/docs/deployment)

