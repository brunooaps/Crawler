# 🎮 Games Crawler & Management System

Questo progetto è una soluzione full-stack sviluppata per la sfida
tecnica di Oxylabs. Il sistema implementa un ecosistema completo per lo
scraping di dati di prodotti (videogiochi), la loro elaborazione
asincrona tramite code e un'interfaccia moderna per la visualizzazione e
la gestione dei record.

------------------------------------------------------------------------

## 🚀 Stack Tecnologico

-   **Backend Framework:** Laravel 11 (PHP 8.2+)
-   **Infrastruttura:** Laravel Sail (Docker)
-   **Scraping:** Guzzle & Symfony DomCrawler
-   **Data Processing:** Laravel Queues (Database Driver)
-   **Admin Panel:** Filament PHP v3
-   **Frontend UI:** Livewire 3 & TailwindCSS

------------------------------------------------------------------------

## 🛠️ Installazione e Configurazione

Seguire i passaggi seguenti per avviare il progetto localmente
utilizzando Docker.

### 1. Clonare la Repository

``` bash
git clone https://github.com/brunooaps/Crawler.git
cd Crawler
```

### 2. Avviare l'Ambiente (Sail)

``` bash
./vendor/bin/sail up -d
```

### 3. Installare le Dipendenze ed Eseguire le Migrazioni

``` bash
./vendor/bin/sail composer install
./vendor/bin/sail artisan migrate
```

### 4. Creare l'Utente Amministratore (Pannello Filament)

``` bash
./vendor/bin/sail artisan make:filament-user
```

------------------------------------------------------------------------

## 🏃 Esecuzione del Flusso di Dati

Il progetto è progettato per essere resiliente e scalabile, utilizzando
le **code Laravel** per il processamento dei dati.

### Avviare il Queue Worker

In un terminale dedicato eseguire:

``` bash
./vendor/bin/sail artisan queue:work
```

### Eseguire il Crawler

In un nuovo terminale eseguire:

``` bash
./vendor/bin/sail artisan scrape:products
```

**Nota:**\
Questo comando estrae i dati, salva un backup in:

`storage/app/products_timestamp.json`

e invia una richiesta **POST** all'API di importazione.

------------------------------------------------------------------------

## 🖥️ Interfacce Disponibili

### 1️⃣ Catalogo Utente (Frontend)

Disponibile all'indirizzo:

http://localhost/view/products

Funzionalità:

-   Interfaccia moderna sviluppata con **Livewire**
-   **Ricerca in tempo reale** (Debounce 300ms)
-   **Ordinamento dinamico** per ID, Titolo e Prezzo
-   **Badge per le categorie**
-   **Anteprime delle immagini**

------------------------------------------------------------------------

### 2️⃣ Pannello Amministrativo (Filament)

Disponibile all'indirizzo:

http://localhost/admin

Funzionalità:

-   Gestione completa **CRUD** dei prodotti
-   Visualizzazione immagini tramite **anteprima circolare**
-   **Modifica ed eliminazione** dei record

------------------------------------------------------------------------

## 📝 Dettagli Tecnici di Implementazione

### Strategia di Scraping Ibrida

Il crawler utilizza:

-   **Selettori CSS** per elementi dinamici
-   Estrazione diretta dallo script `__NEXT_DATA__`

Questo approccio garantisce l'integrità dei metadati come **generi e
descrizioni**.

------------------------------------------------------------------------

### Sanitizzazione dei Dati

Implementata la pulizia dei dati tramite:

-   Conversione dei prezzi dal **formato europeo a Float**
-   Conversione dei **percorsi relativi delle immagini in URL assoluti**

------------------------------------------------------------------------

### Struttura del Database

Relazione **1:N** tra:

-   `products`
-   `images`

Questo permette il supporto a **più immagini per ogni prodotto**.

------------------------------------------------------------------------

### Gestione API

Endpoint disponibile:

`POST /api/import`

Riceve i dati e delega il processamento a un **ImportProductsJob**,
migliorando performance e scalabilità.

------------------------------------------------------------------------

## 🍻 Conclusione

Il progetto soddisfa tutti i requisiti obbligatori della prova tecnica e
introduce miglioramenti in termini di **UX e performance** grazie
all'uso di **code asincrone e componenti reattivi**.
