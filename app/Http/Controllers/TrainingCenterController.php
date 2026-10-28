<?php

namespace App\Http\Controllers;

use App\Models\TrainingCenter;
use Illuminate\Http\Request;

class TrainingCenterController extends Controller
{
    public function index()
    {
        return response()->json(TrainingCenter::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'location' => 'required|max:255',
        ]);

        $trainingCenter = TrainingCenter::create($request->all());

        return response()->json($trainingCenter, 201);
    }

    public function show($id)
    {
        $trainingCenter = TrainingCenter::with(['teachers', 'courses'])->findOrFail($id);

        return response()->json($trainingCenter, 200);
    }

    public function update(Request $request, $id)
    {
        $trainingCenter = TrainingCenter::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'location' => 'required|max:255',
        ]);

        $trainingCenter->update($request->all());

        return response()->json($trainingCenter, 200);
    }
}
