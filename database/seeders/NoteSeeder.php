<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    public function run(): void
    {
        Note::create([
            'title' => 'Projekt-Deadline',
            'content' => 'Das neue Feature muss bis Ende der Woche fertig sein. Meeting am Freitag um 14:00 Uhr zur Präsentation.',
            'is_important' => true,
        ]);

        Note::create([
            'title' => 'Zahnarzttermin',
            'content' => 'Termin am Donnerstag, 15:30 Uhr. Adresse: Hauptstraße 123, nicht vergessen!',
            'is_important' => true,
        ]);

        Note::create([
            'title' => 'Geburtstagsgeschenk für Maria',
            'content' => 'Ideen: Neues Buch von ihrem Lieblingsautor, Konzertkarten oder ein Gutschein für das italienische Restaurant.',
            'is_important' => true,
        ]);

        Note::create([
            'title' => 'Einkaufsliste',
            'content' => 'Milch, Brot, Käse, Tomaten, Gurken, Olivenöl, Pasta, Kaffee',
            'is_important' => false,
        ]);

        Note::create([
            'title' => 'Laravel Best Practices',
            'content' => 'Service Container nutzen, Repository Pattern implementieren, Eloquent Relationships optimal verwenden. Cache für häufige Queries einsetzen.',
            'is_important' => false,
        ]);

        Note::create([
            'title' => 'Vue 3 Composition API',
            'content' => 'Vorteile: Bessere Code-Organisation, einfachere Wiederverwendbarkeit, TypeScript-Unterstützung. ref() für primitive Werte, reactive() für Objekte.',
            'is_important' => false,
        ]);

        Note::create([
            'title' => 'Urlaubsplanung 2025',
            'content' => 'Mögliche Ziele: Portugal (Lissabon & Porto), Griechenland (Santorini), oder Roadtrip durch Österreich. Budget: ca. 2000€',
            'is_important' => false,
        ]);

        Note::create([
            'title' => 'Workout-Routine',
            'content' => 'Montag: Brust & Trizeps, Mittwoch: Rücken & Bizeps, Freitag: Beine & Schultern. Cardio 2x pro Woche.',
            'is_important' => false,
        ]);

        Note::create([
            'title' => 'Buchempfehlungen',
            'content' => 'Clean Code von Robert Martin, Design Patterns, The Pragmatic Programmer, Refactoring von Martin Fowler',
            'is_important' => false,
        ]);

        Note::create([
            'title' => 'API-Design Guidelines',
            'content' => 'RESTful principles beachten, konsistente Namenskonventionen, aussagekräftige HTTP-Status-Codes, Versionierung der API.',
            'is_important' => true,
        ]);

        Note::create([
            'title' => 'Meeting-Notizen',
            'content' => 'Team hat neue Sprint-Ziele definiert. Fokus auf Performance-Optimierung und UX-Verbesserungen. Nächstes Daily um 9:00 Uhr.',
            'is_important' => false,
        ]);

        Note::create([
            'title' => 'Rezept: Pasta Carbonara',
            'content' => 'Zutaten: Spaghetti, Eier, Parmesan, Pancetta, schwarzer Pfeffer. Pasta al dente kochen, mit Ei-Käse-Mischung vermengen.',
            'is_important' => false,
        ]);

        Note::create([
            'title' => 'Code Review Checkliste',
            'content' => 'Lesbarkeit prüfen, Performance berücksichtigen, Security-Aspekte beachten, Tests vorhanden?, Dokumentation aktuell?',
            'is_important' => true,
        ]);

        Note::create([
            'title' => 'Podcast-Empfehlungen',
            'content' => 'Software Engineering Daily, Syntax.fm, Laravel News Podcast, The Changelog',
            'is_important' => false,
        ]);

        Note::create([
            'title' => 'Git Workflow',
            'content' => 'Feature-Branches erstellen, regelmäßig committen mit aussagekräftigen Messages, Pull Requests für Code Review nutzen.',
            'is_important' => false,
        ]);

        Note::factory()->count(10)->create();
    }
}
