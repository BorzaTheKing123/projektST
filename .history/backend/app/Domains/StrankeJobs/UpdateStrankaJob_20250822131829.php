<?php

namespace App\Domains\StrankeJobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Stranka;

class UpdateStrankaJob
{
    public function __construct(private $stranka, private $request, private $info)
    {

    }

    public function handle()
    {
        $info = Stranka::where('name', $this->stranka)->where('user_id', Auth::id())->first();
        if (Auth::id() == $info->user_id)
        {
            $info = $this->request->input();
            $data = Stranka::where('name', $this->stranka)->where('user_id', Auth::id())->update($info);
            return response()->json($data);
        }

        return response()->json([
            'message' => 'Email je bil že uporabljen!'
        ], 409);
    }
}
