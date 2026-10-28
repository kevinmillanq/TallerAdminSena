<?php

namespace App\Http\Controllers;

use App\Models\Apprentice;
use Illuminate\Http\Request;

class ApprenticeController extends Controller
{
    public function index()
    {
        return response()->json(Apprentice::with(['course', 'computer'])->get(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:apprentices,email',
            'cell_number' => 'required|max:20',
            'course_id' => 'required|exists:courses,id',
            'computer_id' => 'required|exists:computers,id',
        ]);

        $apprentice = Apprentice::create($request->all());

        return response()->json($apprentice, 201);
    }

    public function show($id)
    {
        $apprentice = Apprentice::with(['course', 'computer'])->findOrFail($id);

        return response()->json($apprentice, 200);
    }

    public function update(Request $request, $id)
    {
        $apprentice = Apprentice::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:apprentices,email,' . $id,
            'cell_number' => 'required|max:20',
            'course_id' => 'required|exists:courses,id',
            'computer_id' => 'required|exists:computers,id',
        ]);

        $apprentice->update($request->all());

        return response()->json($apprentice, 200);
    }
}
