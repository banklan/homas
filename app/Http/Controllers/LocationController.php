<?php

namespace App\Http\Controllers;
use App\Location;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getAll(){
        $locations = Location::all();

        return response()->json($locations, 200);
    }

    public function getPopular(){
        $locs = Location::where('is_popular', true) -> get();

        return response()->json($locs, 200);
    }

    public function getLocationById($id){
        $loc = Location::findOrFail($id);

        return response()->json($loc, 200);
    }
}
