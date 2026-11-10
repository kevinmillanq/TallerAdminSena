<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class ComputerController extends Controller
{
    public function index()
    {
        return response()->json(Computer::with('image')->get(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|max:255',
            'brand' => 'required|max:255',
            'image' => 'nullable|image|max:5120',
        ]);

        $computer = Computer::create($request->only('number', 'brand'));

        if ($request->hasFile('image')) {
            $cloudinaryUrl = config('cloudinary.cloud_url') ?: env('CLOUDINARY_URL');
            if (!empty($cloudinaryUrl)) {
                $uploaded = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => 'adminsena/computers',
                ]);
                $computer->image()->create([
                    'url' => $uploaded->getSecurePath(),
                    'public_id' => $uploaded->getPublicId(),
                ]);
            }
        }

        $computer->load('image');

        return response()->json($computer, 201);
    }

    public function show($id)
    {
        $computer = Computer::with(['apprentices', 'image'])->findOrFail($id);

        return response()->json($computer, 200);
    }

    public function update(Request $request, $id)
    {
        $computer = Computer::findOrFail($id);

        $request->validate([
            'number' => 'required|max:255',
            'brand' => 'required|max:255',
            'image' => 'nullable|image|max:5120',
        ]);

        $computer->update($request->only('number', 'brand'));

        if ($request->hasFile('image')) {
            $cloudinaryUrl = config('cloudinary.cloud_url') ?: env('CLOUDINARY_URL');
            if (!empty($cloudinaryUrl)) {
                $uploaded = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => 'adminsena/computers',
                ]);

                if ($computer->image) {
                    if ($computer->image->public_id) {
                        try {
                            Cloudinary::destroy($computer->image->public_id);
                        } catch (\Exception $e) {}
                    }
                    $computer->image->update([
                        'url' => $uploaded->getSecurePath(),
                        'public_id' => $uploaded->getPublicId(),
                    ]);
                } else {
                    $computer->image()->create([
                        'url' => $uploaded->getSecurePath(),
                        'public_id' => $uploaded->getPublicId(),
                    ]);
                }
            }
        }

        $computer->load('image');

        return response()->json($computer, 200);
    }
}
