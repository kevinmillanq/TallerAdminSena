<?php

namespace App\Http\Controllers;

use App\Models\TrainingCenter;
use Illuminate\Http\Request;

class TrainingCenterController extends Controller
{
    public function index()
    {
        $trainingCenters = TrainingCenter::with(['teachers', 'courses'])->get();
        return response()->json($trainingCenters, 200);
    }
}
