<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use Illuminate\Http\Request;

class ComputerController extends Controller
{
    public function index()
    {
        return response()->json(Computer::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|max:255',
            'brand' => 'required|max:255',
        ]);

        $computer = Computer::create($request->all());

        return response()->json($computer, 201);
    }

    public function show($id)
    {
        $computer = Computer::with('apprentices')->findOrFail($id);

        return response()->json($computer, 200);
    }

    public function update(Request $request, $id)
    {
        $computer = Computer::findOrFail($id);

        $request->validate([
            'number' => 'required|max:255',
            'brand' => 'required|max:255',
        ]);

        $computer->update($request->all());

        return response()->json($computer, 200);
    }
}
