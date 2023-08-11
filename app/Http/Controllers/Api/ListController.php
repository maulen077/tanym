<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cat;
use App\Models\City;

class ListController extends Controller
{
    public function catsList()
    {
        $cats = Cat::all();
        return response()->json(['cats' => $cats]);
    }

    public function citiesList()
    {
        $cities = City::all();
        return response()->json(['cities' => $cities]);
    }
}
