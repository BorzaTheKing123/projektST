<?php

namespace App\Http\Controllers;

use App\Features\StrankeFeatures\EditStrankaFeature;
use Illuminate\Http\Request;
use App\Features\StrankeFeatures\StoreNewStrankaFeature;
use App\Features\StrankeFeatures\ShowStrankeFeature;
use App\Features\StrankeFeatures\UpdateStrankaFeature;
use App\Features\StrankeFeatures\DeleteStrankaFeature;
use App\Models\Stranka;


class StrankeController extends Controller
{
public function index(Request $request)
{

    $user = $request->user();

    if (!$user) {
        return response()->json(['error' => 'Uporabnik ni prijavljen'], 401);
    }

    $stranke = Stranka::where('user_id', $user->id)->get();

    return response()->json($stranke);
}



    public function create()
    {
        return response()->json(200);
    }

    public function store(Request $request)
    {
        $validated['user_id'] = $request->user()->id;
        $stranka = Stranka::create($validated);

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
