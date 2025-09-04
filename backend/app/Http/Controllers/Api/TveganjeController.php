<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tveganje;
use App\Models\Stranka;

class TveganjeController extends Controller
{
    // ✅ Pridobi vsa tveganja z imenom stranke
    public function index()
    {
        $tveganja = Tveganje::with('stranka')->get();

        return response()->json($tveganja);
    }

    // ✅ Pridobi eno tveganje za urejanje
    public function show($id)
    {
        $tveganje = Tveganje::with('stranka')->findOrFail($id);

        return response()->json($tveganje);
    }

    // ✅ Shrani novo tveganje
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ime' => 'required|string|max:255',
            'stranka_id' => 'required|exists:stranke,id',
            'ukrepi' => 'required|string'
        ]);

        $tveganje = Tveganje::create($validated);

        return response()->json([
            'message' => 'Tveganje uspešno dodano.',
            'data' => $tveganje
        ], 201);
    }

    // ✅ Posodobi obstoječe tveganje
    public function update(Request $request, Tveganje $tveganje)
    {
        $validated = $request->validate([
            'ime' => 'required|string|max:255',
            'stranka_id' => 'required|exists:stranke,id',
            'ukrepi' => 'required|string'
        ]);

        $tveganje->update($validated);

        return response()->json([
            'message' => 'Tveganje uspešno posodobljeno.',
            'data' => $tveganje
        ]);
    }

    // ✅ Izbriši tveganje
    public function destroy(Tveganje $tveganje)
    {
        $tveganje->delete();

        return response()->json([
            'message' => 'Tveganje uspešno izbrisano.'
        ]);
    }
}

