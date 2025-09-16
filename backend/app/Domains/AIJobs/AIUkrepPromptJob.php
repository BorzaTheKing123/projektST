<?php

namespace App\Domains\AIJobs;

class AIUkrepPromptJob
{
    /**
     * Konstruktor za injektiranje odvisnosti ali prejem podatkov.
     */
    public function __construct(private $request)
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
        $tveganjeIme = $this->request->input('tveganje');
        $navodila = $this->request->input('navodila');

        $prompt = <<<EOT
        Si strokovnjak za varnost in tveganja. Na podlagi spodnjih podatkov napiši **izključno** seznam kratkih, jedrnatih ukrepov.

        Tveganje: "$tveganjeIme"
        Navodila: "$navodila"

        ⚠️ Pomembno:
        - Ne dodaj uvoda, razlage ali zaključka.
        - Vsak ukrep naj bo v svoji vrstici.
        - Vsak ukrep naj se konča z vejico.
        - Ne uporabljaj številk ali alinej.

        Primer:
        Gasilni aparat,
        Strelovod,
        Označene poti,

        Začni zdaj:
        EOT;

        return $prompt;
    }
}
