<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::with(['teachers', 'courses'])->get();
        return response()->json($areas, 200);
    }
}
