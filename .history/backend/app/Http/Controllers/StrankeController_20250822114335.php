<?php

namespace App\Http\Controllers;

use App\Features\StrankeFeatures\EditStrankaFeature;
use Illuminate\Http\Request;
use App\Features\StrankeFeatures\StoreNewStrankaFeature;
use App\Features\StrankeFeatures\ShowStrankeFeature;
use App\Features\StrankeFeatures\UpdateStrankaFeature;
use App\Features\StrankeFeatures\DeleteStrankaFeature;
use Illuminate\Support\Facades\Log;

class StrankeController extends Controller
{
    public function index(Int $id)
    {   
        //return $id;
        return new ShowStrankeFeature($id)->handle();
    }

    public function create()
    {
        //return Inertia::render('AddClients', ['id' => Auth::id()]);
    }

    public function store(Request $request)
    {
        Log::info('Kličem handle() iz kontrolerja', ['line' => __LINE__]);
        return (new StoreNewStrankaFeature($request))->handle();
    }

    public function edit(Int $id, String $stranka) // $id je samo zato da sprejme id od userja, ker sta podana 2 parametra
    {   
        return new EditStrankaFeature($stranka)->handle();
    }

    public function update(Int $id, String $stranka, Request $request)
    {
        return new UpdateStrankaFeature($stranka, $request)->handle();
    }

    public function destroy(Int $id, String $stranka)
    {
        return new DeleteStrankaFeature($stranka)->handle();
    }
}
