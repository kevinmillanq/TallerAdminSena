<?php

namespace App\Http\Controllers;

use App\Models\TrainingCenter;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class TrainingCenterController extends Controller
{
    public function index()
    {
        return response()->json(TrainingCenter::with('image')->get(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'location' => 'required|max:255',
            'image' => 'nullable|image|max:5120',
        ]);

        $trainingCenter = TrainingCenter::create($request->only('name', 'location'));

        if ($request->hasFile('image')) {
            $cloudinaryUrl = config('cloudinary.cloud_url') ?: env('CLOUDINARY_URL');
            if (!empty($cloudinaryUrl)) {
                $uploaded = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => 'adminsena/training_centers',
                ]);
                $trainingCenter->image()->create([
                    'url' => $uploaded->getSecurePath(),
                    'public_id' => $uploaded->getPublicId(),
                ]);
            }
        }

        $trainingCenter->load('image');

        return response()->json($trainingCenter, 201);
    }

    public function show($id)
    {
        $trainingCenter = TrainingCenter::with(['teachers', 'courses', 'image'])->findOrFail($id);

        return response()->json($trainingCenter, 200);
    }

    public function update(Request $request, $id)
    {
        $trainingCenter = TrainingCenter::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'location' => 'required|max:255',
            'image' => 'nullable|image|max:5120',
        ]);

        $trainingCenter->update($request->only('name', 'location'));

        if ($request->hasFile('image')) {
            $cloudinaryUrl = config('cloudinary.cloud_url') ?: env('CLOUDINARY_URL');
            if (!empty($cloudinaryUrl)) {
                $uploaded = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => 'adminsena/training_centers',
                ]);

                if ($trainingCenter->image) {
                    if ($trainingCenter->image->public_id) {
                        try {
                            Cloudinary::destroy($trainingCenter->image->public_id);
                        } catch (\Exception $e) {}
                    }
                    $trainingCenter->image->update([
                        'url' => $uploaded->getSecurePath(),
                        'public_id' => $uploaded->getPublicId(),
                    ]);
                } else {
                    $trainingCenter->image()->create([
                        'url' => $uploaded->getSecurePath(),
                        'public_id' => $uploaded->getPublicId(),
                    ]);
                }
            }
        }

        $trainingCenter->load('image');

        return response()->json($trainingCenter, 200);
    }
}
