<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; // <-- PRAVILNA VRSTICA

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Ta klic bo zdaj deloval pravilno, ker uporablja fasado
Schedule::command('scraper:run')->everyTenMinutes();