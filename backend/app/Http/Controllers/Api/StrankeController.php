<?php

namespace App\Http\Controllers\Api;

use App\Features\StrankeFeatures\EditStrankaFeature;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Features\StrankeFeatures\StoreNewStrankaFeature;
use App\Features\StrankeFeatures\ShowStrankeFeature;
use App\Features\StrankeFeatures\UpdateStrankaFeature;
use App\Features\StrankeFeatures\DeleteStrankaFeature;

class StrankeController extends Controller
{
    public function index(Request $request)
    {
        // 🔍 Logiranje Authorization headerja za test
        \Log::info('Authorization header: ' . $request->header('Authorization'));

        // ✅ Po želji lahko header pošlješ nazaj v response (če frontend mora videti)
        return response()->json((new ShowStrankeFeature())->handle())
                         ->header('Authorization', $request->header('Authorization'));
    }

    public function create()
    {
        return response()->json(200);
    }

    public function store(Request $request)
    {
        return (new StoreNewStrankaFeature($request))->handle();
    }

    public function edit(String $stranka)
    {
        return new EditStrankaFeature($stranka)->handle();
    }

    public function update(String $stranka, Request $request)
    {
        return new UpdateStrankaFeature($stranka, $request)->handle();
    }

    public function destroy(String $stranka)
    {
        return new DeleteStrankaFeature($stranka)->handle();
    }
}

