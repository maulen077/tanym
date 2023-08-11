<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Matches;

class RoundController extends Controller
{
    public function show($matchId)
    {
        $match = Matches::all();
        $rounds = $match->round;
        return response()->json(['round' => $rounds]);
    }



}
