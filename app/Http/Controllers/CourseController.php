<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return response()->json(Course::with(['area', 'trainingCenter', 'apprentices'])->get(), 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (isset($data['course_number']) && !isset($data['course_name'])) {
            $data['course_name'] = $data['course_number'];
        }
        $request->merge($data);

        $request->validate([
            'course_name' => 'required|max:255',
            'day' => 'required',
            'area_id' => 'nullable|exists:areas,id',
            'training_center_id' => 'nullable|exists:training_centers,id',
        ]);

        $course = Course::create($request->all());

        return response()->json($course, 201);
    }

    public function show($id)
    {
        $course = Course::with(['area', 'trainingCenter', 'apprentices'])->findOrFail($id);

        return response()->json($course, 200);
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $data = $request->all();
        if (isset($data['course_number']) && !isset($data['course_name'])) {
            $data['course_name'] = $data['course_number'];
        }
        $request->merge($data);

        $request->validate([
            'course_name' => 'required|max:255',
            'day' => 'required',
            'area_id' => 'nullable|exists:areas,id',
            'training_center_id' => 'nullable|exists:training_centers,id',
        ]);

        $course->update($request->all());

        return response()->json($course, 200);
    }
}
