<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stranka;
use App\Features\StrankeFeatures\EditStrankaFeature;
use App\Features\StrankeFeatures\StoreNewStrankaFeature;
use App\Features\StrankeFeatures\ShowStrankeFeature;
use App\Features\StrankeFeatures\UpdateStrankaFeature;
use App\Features\StrankeFeatures\DeleteStrankaFeature;

class StrankeController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            (new ShowStrankeFeature())->handle())->header('Authorization', $request->header('Authorization')
        );
    }

    public function create()
    {
        return response()->json(200);
    }

    public function store(Request $request)
    {
        return (new StoreNewStrankaFeature($request))->handle();
    }

    public function edit(Stranka $stranka)
    {
        return (new EditStrankaFeature($stranka))->handle();
    }

    public function update(Stranka $stranka, Request $request)
    {
        return (new UpdateStrankaFeature($stranka, $request))->handle();
    }

    public function destroy(Stranka $stranka)
    {
        return (new DeleteStrankaFeature($stranka))->handle();
    }

    public function show($id)
    {
        return response()->json(Stranka::findOrFail($id));
    }

    public function mojeStranke(Request $request)
    {
        return response()->json(Stranka::where('user_id', $request->user()->id)->get());
    }
}

