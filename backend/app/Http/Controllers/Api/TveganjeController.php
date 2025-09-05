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

    // ✅ Pridobi eno tveganja za urejanje
    public function show($id)
    {
        $tveganja = Tveganje::with('stranka')->findOrFail($id);

        return response()->json($tveganja);
    }

    // ✅ Shrani novo tveganja
    public function store(Request $request)
    {

        $validated = $request->validate([
            'ime' => 'required|string|max:255',
            'stranka_id' => 'required|exists:stranke,id',
            'ukrepi' => 'required|string'
        ]);

        $tveganja = Tveganje::create($validated);

        return response()->json([
            'message' => 'Tveganje uspešno dodano.',
            'data' => $tveganja
        ], 201);
    }

    // ✅ Posodobi obstoječe tveganja
    public function update(Request $request, Tveganje $tveganja)
    {
    $validated = $request->validate([
        'ime' => 'required|string|max:255',
        'stranka_id' => 'required|exists:stranke,id',
        'ukrepi' => 'required|string'
    ]);

    \Log::info('Prejemam za posodobitev:', $validated);

    $user = $request->user();
    $stranka = \App\Models\Stranka::where('id', $validated['stranka_id'])
        ->where('user_id', $user->id)
        ->first();

    if (!$stranka) {
        return response()->json([
            'message' => 'Ne moreš posodobiti tveganja za stranko, ki ni tvoja.'
        ], 403);
    }

    $updated = $tveganja->update($validated);
    \Log::info('Ali je posodobitev uspela?', ['status' => $updated]);

    $tveganja->refresh();

    return response()->json([
        'message' => 'Tveganje uspešno posodobljeno.',
        'data' => $tveganja
    ]);
    }
    // ✅ Izbriši tveganja
    public function destroy(Tveganje $tveganja)
    {
        $tveganja->delete();

        return response()->json([
            'message' => 'Tveganje uspešno izbrisano.'
        ]);
    }
    public function zaStranko($strankaId)
    {

        $tveganja = \App\Models\Tveganje::where('stranka_id', $strankaId)->get();

        return response()->json($tveganja);
    }

}

