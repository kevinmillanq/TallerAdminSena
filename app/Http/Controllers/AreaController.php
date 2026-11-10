<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Image;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::with(['teachers', 'courses', 'image'])->get();
        return response()->json($areas, 200);
    }

    // vista formulario
    public function create()
    {
        return view('areas.create');
    }

    // post recibe datos formulario y crea registro
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'image' => 'nullable|image|max:5120',
        ]);

        $area = Area::create($request->only('name'));

        // Si se adjunta imagen, subir a Cloudinary y asociarla
        if ($request->hasFile('image')) {
            $cloudinaryUrl = config('cloudinary.cloud_url') ?: env('CLOUDINARY_URL');
            if (!empty($cloudinaryUrl)) {
                $uploaded = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => 'adminsena/areas',
                ]);
                $area->image()->create([
                    'url' => $uploaded->getSecurePath(),
                    'public_id' => $uploaded->getPublicId(),
                ]);
            }
        }

        $area->load('image');

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($area, 201);
        }

        return redirect()->route('areas.create')->with('success', 'Área creada correctamente');
    }

    // UPDATE recibe nuevos datos y actualiza existentes
    public function update(Request $request, Area $area)
    {
        // valida campo
        $request->validate([
            'name' => 'required|max:255',
            'image' => 'nullable|image|max:5120',
        ]);

        // actualiza con los datos nuevos
        $area->update($request->only('name'));

        // Si se adjunta nueva imagen
        if ($request->hasFile('image')) {
            $cloudinaryUrl = config('cloudinary.cloud_url') ?: env('CLOUDINARY_URL');
            if (!empty($cloudinaryUrl)) {
                $uploaded = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => 'adminsena/areas',
                ]);

                if ($area->image) {
                    if ($area->image->public_id) {
                        try {
                            Cloudinary::destroy($area->image->public_id);
                        } catch (\Exception $e) {}
                    }
                    $area->image->update([
                        'url' => $uploaded->getSecurePath(),
                        'public_id' => $uploaded->getPublicId(),
                    ]);
                } else {
                    $area->image()->create([
                        'url' => $uploaded->getSecurePath(),
                        'public_id' => $uploaded->getPublicId(),
                    ]);
                }
            }
        }

        $area->load('image');

        // devuelve el registro ya cambiado
        return response()->json($area);
    }

    // metodo show
    public function show($id)
    {
        return response()->json(Area::with(['teachers', 'courses', 'image'])->findOrFail($id));
    }
}
