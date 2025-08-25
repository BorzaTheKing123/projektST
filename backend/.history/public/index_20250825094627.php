<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

// 1. Definicija poti in nalaganje avtoload datoteke
define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

// 2. Ustvarjanje instance aplikacije
$app = require_once __DIR__.'/../bootstrap/app.php';

// 3. Obdelava zahteve
$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

// 4. Zaključek obdelave
$kernel->terminate($request, $response);