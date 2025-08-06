# Muggshot-plugin

WordPress-pluginet som hämtar och visar publika Instagram-inlägg baserat på hashtags. Används på [muggshot.se](https://muggshot.se).

## Funktioner
- Hämtar inlägg via hashtag
- Lagrar bilder lokalt
- Shortcode för visning i grid
- Röstsättning med stjärnor
- Topplistor baserat på röster
- Automatisk arkivering av gamla inlägg
- Adminpanel för inställningar och statistik

## Mappstruktur
- `/_pre historic/` – äldre versioner, bevarade för referens
- `/muggshot-plugin-v0.x.x/` – olika versionsmappar
- `/old/` – övrigt äldre innehåll
- `scrape_instagram.py` – experimentellt script för direktimport från publika Instagramflöden

## Installation
1. Gå till senaste `muggshot-plugin-v...`-mappen
2. Zippa den och installera via WordPress → Tillägg → Lägg till nytt

## Shortcode-exempel
```php
[muggshot hashtag="muggshotse" antal="12" kolumner="3"]
```

## Bidrag
Alla förbättringar, buggrapporter eller feature requests välkomnas!
