<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RiskSeeder extends Seeder
{
    public function run(): void
    {
        $risks = [
            // Geopolitična in Makroekonomska Tveganja
            ['Politična tveganja', 'Spremembe vlad, politična nestabilnost, protekcionizem, politični konflikti'],
            ['Vojna in varnost', 'Oboroženi spopadi, terorizem, napadi na infrastrukturo'],
            ['Makroekonomska tveganja', 'Recesija, inflacija, deflacija, spremembe obrestnih mer'],
            ['Valutna tveganja', 'Nihanje tečajev in devalvacija valute'],
            ['Regulativna tveganja', 'Nove zakonodaje, višji davki, strožji predpisi'],
            ['Tržna tveganja', 'Nihanje cen na trgu (npr. surovine, delnice)'],

            // Operativna in Tehnološka Tveganja
            ['Operativna tveganja', 'Napake v procesih, človeške napake, slab nadzor kakovosti'],
            ['Tveganja dobavne verige', 'Zamude, pomanjkanje materialov, zanesljivost dobaviteljev'],
            ['Tehnološka tveganja', 'Okvare strojev, izpadi programske opreme, zastarela tehnologija'],
            ['Kibernetska tveganja', 'Vdori, izsiljevalski virusi, phishing, kraje podatkov'],
            ['Tveganja IT-infrastrukture', 'Izpadi strežnikov, napake v omrežju'],
            ['Tveganja informacijske varnosti', 'Uhajanje zaupnih podatkov, nepooblaščen dostop'],

            // Okoljska in Družbena Tveganja (ESG)
            ['Okoljska tveganja', 'Naravne katastrofe (potresi, poplave), podnebne spremembe, onesnaženje, pomanjkanje virov'],
            ['Zdravstvena tveganja', 'Pandemije, izbruhi bolezni, zdravstvene krize'],
            ['Družbena tveganja', 'Delavski spori, družbeni nemiri, kršitve človekovih pravic'],
            ['Tveganja ugleda', 'Škandal, slab PR, izguba zaupanja strank in javnosti'],
            ['Etična tveganja', 'Neetično poslovanje, korupcija, prevare'],

            // Finančna in Pravna Tveganja
            ['Kreditno tveganje', 'Neplačila dolga s strani strank ali partnerjev'],
            ['Likvidnostno tveganje', 'Nezmožnost izpolnjevanja kratkoročnih finančnih obveznosti'],
            ['Tveganja skladnosti s predpisi', 'Neupoštevanje zakonov in regulativnih zahtev, kar vodi v kazni ali globe'],
            ['Pravna tveganja', 'Tožbe, spori, neizpolnjene pogodbe'],

            // Tveganja na ravni projekta
            ['Tveganja projekta', 'Preseganje proračuna, zamude, spremembe obsega projekta'],
            ['Tveganja pomanjkanja znanja', 'Nepravilno razporejeni viri, pomanjkanje usposobljenega kadra'],
        ];

        foreach ($risks as [$category, $opis]) {
            DB::table('risks')->insert([
                'category' => $category,
                'opis' => $opis,
                'article_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}