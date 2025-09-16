<?php

namespace App\Domains\AIJobs;

class AIScrapePromptJob
{
    /**
     * Konstruktor za injektiranje odvisnosti ali prejem podatkov.
     */
    public function __construct(private $data)
    {
        //
    }

    /**
     * Izvede glavno logiko.
     *
     * @param  mixed  ...$parameters
     * @return mixed
     */
    public function handle()
    {
        $link = $this->data["link"];
        $summary = $this->data['summary'];
        $text = $this->data['text'];


        $prompt = <<<EOT
        VLOGA:
        Deluj kot visoko usposobljen strokovnjak za analizo varnostnih tveganj.

        CILJ:
        Tvoja naloga je, da na podlagi posredovanih podatkov o novici (povezava, povzetek in besedilo) identificiraš **glavno oziroma prevladujoče tveganje**, opisano v vsebini, ga uvrstiš v eno izmed spodaj navedenih kategorij in pripraviš odgovor v strogo določenem formatu JSON.

        ---

        VHODNI PODATKI:
        * **Link:** {$link}
        * **Povzetek:** {$summary}
        * **Besedilo:** {$text}

        ---

        NAVODILA ZA ANALIZO:
        1.  **Preberi in razumi vsebino:** Natančno preuči posredovano besedilo, da razumeš osrednji dogodek ali temo.
        2.  **Identificiraj glavno tveganje:** Osredotoči se na **največ 3** najpomembnejša tveganja, ki izhajajo iz vsebine. Če je tveganj več, izberi tista 3, ki so najbolj poudarjena.
        3.  **Dodeli kategorijo:** Izberi najustreznejše kategorije iz spodnjega seznama. Uporabiš lahko **samo eno** kategorijo na 1 JSON zapis.

        ---

        SEZNAM DOVOLJENIH KATEGORIJ Tveganj:
        Uporabi **točno eno** izmed naslednjih vrednosti za ključ `category`. Ime kategorije mora biti zapisano z veliko začetnico, ostale črke pa z malo.

        * `Politična tveganja`
        * `Vojna in varnost`
        * `Makroekonomska tveganja`
        * `Valutna tveganja`
        * `Regulativna tveganja`
        * `Tržna tveganja`
        * `Operativna tveganja`
        * `Tveganja dobavne verige`
        * `Tehnološka tveganja`
        * `Kibernetska tveganja`
        * `Tveganja IT-infrastrukture`
        * `Tveganja informacijske varnosti`
        * `Okoljska tveganja`
        * `Zdravstvena tveganja`
        * `Družbena tveganja`
        * `Tveganja ugleda`
        * `Etična tveganja`
        * `Kreditno tveganje`
        * `Likvidnostno tveganje`
        * `Tveganja skladnosti s predpisi`
        * `Pravna tveganja`
        * `Tveganja projekta`
        * `Tveganja pomanjkanja znanja`

        ---

        ZAHTEVAN IZHODNI FORMAT (JSON):
        Odgovor **mora biti** v formatu JSON in vsebovati seznam (list) z enim slovarjem (dictionary). Ne dodajaj nobenega dodatnega besedila ali pojasnil izven JSON strukture.

        **Struktura slovarja:**
        * `link` (string): Uporabi natančno vrednost, ki je bila posredovana v vhodnih podatkih.
        * `category` (string): Ena izmed vrednosti iz zgoraj navedenega seznama dovoljenih kategorij.
        * `summary` (string): Tvoj lasten, kratek povzetek osrednjega tveganja, dolg **največ 20 besed**.
        * `confidence` (float): Decimalno število med **0.0 in 1.0**, ki predstavlja tvojo stopnjo zanesljivosti pri določanju kategorije (npr. 0.75, 0.9, 1.0).

        ---

        PRIMER ZAHTEVANEGA JSON ODGOVORA:
        [
            {
                'link': 'https://www.24ur.com/novice/tujina/silovito-neurje-v-mostarju-na-reki-so-se-ulice-spremenile-v-hudournike.html',
                'category': 'Okoljska tveganja',
                'summary': 'Obilne padavine in neurja so povzročila obsežne poplave in materialno škodo v več regijah.',
                'confidence': 1.0
            },
            {
                'link': 'https://www.24ur.com/novice/tujina/silovito-neurje-v-mostarju-na-reki-so-se-ulice-spremenile-v-hudournike.html',
                'category': 'Operativna tveganja',
                'summary': 'Poplave in zemeljski plazovi otežujejo vožnjo in prekinjajo trajektne, katamaranske in ladijske linije.',
                'confidence': 0.9
            },
            {
                'link': 'https://www.24ur.com/novice/tujina/silovito-neurje-v-mostarju-na-reki-so-se-ulice-spremenile-v-hudournike.html',
                'category': 'Tveganja projekta',
                'summary': 'Nujne službe so imele zaradi poplavljenih cest in prostorov čez dan okoli 60 intervencij.',
                'confidence': 0.8
            }
        ]
        EOT;

        return $prompt;
    }
}