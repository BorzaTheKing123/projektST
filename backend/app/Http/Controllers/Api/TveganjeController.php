<?php

namespace App\Http\Controllers\Api;

use App\Features\TveganjeFeatures\DeleteTveganjeFeature;
use App\Features\TveganjeFeatures\StoreNewTveganjeFeature;
use App\Features\TveganjeFeatures\UpdateTveganjeFeature;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tveganje;
use App\Models\Stranka;

class TveganjeController extends Controller
{
    // ✅ Pridobi vsa tveganja z imenom stranke
    public function index()
    {
        return response()->json(Tveganje::with('stranka')->get());
    }

    // ✅ Pridobi eno tveganja za urejanje
    public function show($id)
    {
        return response()->json(Tveganje::with('stranka')->findOrFail($id));
    }

    // ✅ Shrani novo tveganja
    public function store(Request $request)
    {
        return new StoreNewTveganjeFeature($request)->handle();
    }

    // ✅ Posodobi obstoječe tveganja
    public function update(Request $request, Tveganje $tveganja)
    {
        return new UpdateTveganjeFeature($request, $tveganja)->handle();
    }
    // ✅ Izbriši tveganja
    public function destroy(Tveganje $tveganja)
    {
        return new DeleteTveganjeFeature($tveganja)->handle();
    }
    public function zaStranko($strankaId)
    {
        return response()->json(Tveganje::where('stranka_id', $strankaId)->get());
    }

}

