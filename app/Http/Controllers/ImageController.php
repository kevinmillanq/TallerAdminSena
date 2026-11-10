<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Area;
use App\Models\TrainingCenter;
use App\Models\Computer;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Exception;

class ImageController extends Controller
{
    /**
     * Mapeo de tipos soportados para la relación polimórfica.
     */
    protected array $morphMap = [
        'area' => Area::class,
        'areas' => Area::class,
        'Area' => Area::class,
        'training_center' => TrainingCenter::class,
        'training_centers' => TrainingCenter::class,
        'TrainingCenter' => TrainingCenter::class,
        'computer' => Computer::class,
        'computers' => Computer::class,
        'Computer' => Computer::class,
    ];

    /**
     * Listar todas las imágenes con su entidad relacionada.
     */
    public function index(Request $request)
    {
        $images = Image::with('imageable')->latest()->get();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($images, 200);
        }

        return view('images.index', compact('images'));
    }

    /**
     * Vista de formulario para adjuntar y subir imagen (Área, Centro de Formación o Computador).
     */
    public function create()
    {
        $areas = Area::all();
        $trainingCenters = TrainingCenter::all();
        $computers = Computer::all();

        return view('images.create', compact('areas', 'trainingCenters', 'computers'));
    }

    /**
     * Subir imagen a Cloudinary y asociarla polimórficamente a Area, TrainingCenter o Computer.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'imageable_type' => 'required|string',
            'imageable_id' => 'required|integer',
        ], [
            'image.required' => 'Debe adjuntar un archivo de imagen.',
            'image.image' => 'El archivo debe ser una imagen válida.',
            'image.max' => 'La imagen no debe superar los 5MB.',
            'imageable_type.required' => 'Debe seleccionar el tipo de entidad (Area, TrainingCenter o Computer).',
            'imageable_id.required' => 'Debe seleccionar el registro al que pertenecerá la imagen.',
        ]);

        // Verificar que Cloudinary esté configurado en el .env
        $cloudinaryUrl = config('cloudinary.cloud_url') ?: env('CLOUDINARY_URL');
        if (empty($cloudinaryUrl)) {
            $msg = 'Cloudinary no está configurado. Por favor define CLOUDINARY_URL en el archivo .env (ej: cloudinary://API_KEY:API_SECRET@CLOUD_NAME)';
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => $msg], 500);
            }
            return redirect()->back()->withInput()->with('error', $msg);
        }

        // Resolver la clase del modelo según el tipo escogido
        $type = $request->input('imageable_type');
        $modelClass = $this->resolveModelClass($type);

        if (!$modelClass) {
            $msg = "El tipo '{$type}' no es válido. Debe escoger entre: Area, TrainingCenter o Computer.";
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => $msg], 422);
            }
            return redirect()->back()->withInput()->with('error', $msg);
        }

        // Buscar el registro destino
        $model = $modelClass::find($request->input('imageable_id'));
        if (!$model) {
            $msg = "No se encontró el registro con ID {$request->input('imageable_id')} para {$type}.";
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => $msg], 404);
            }
            return redirect()->back()->withInput()->with('error', $msg);
        }

        // Subir a Cloudinary
        try {
            $file = $request->file('image');
            $folderName = 'adminsena/' . strtolower(class_basename($modelClass));

            $uploaded = Cloudinary::upload($file->getRealPath(), [
                'folder' => $folderName,
            ]);

            $secureUrl = $uploaded->getSecurePath();
            $publicId = $uploaded->getPublicId();

            // Guardar registro polimórfico en la tabla images
            $image = Image::create([
                'url' => $secureUrl,
                'public_id' => $publicId,
                'imageable_type' => $modelClass,
                'imageable_id' => $model->id,
            ]);

            $image->load('imageable');

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Imagen subida exitosamente a Cloudinary',
                    'data' => $image,
                ], 201);
            }

            return redirect()->route('images.index')->with('success', '¡Imagen subida exitosamente a Cloudinary y asociada a ' . class_basename($modelClass) . '!');
        } catch (Exception $e) {
            $errorMsg = 'Error al subir la imagen a Cloudinary: ' . $e->getMessage();
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => $errorMsg], 500);
            }
            return redirect()->back()->withInput()->with('error', $errorMsg);
        }
    }

    /**
     * Ver una imagen específica.
     */
    public function show($id, Request $request)
    {
        $image = Image::with('imageable')->findOrFail($id);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($image, 200);
        }

        return view('images.show', compact('image'));
    }

    /**
     * Eliminar una imagen de Cloudinary y de la base de datos.
     */
    public function destroy($id, Request $request)
    {
        $image = Image::findOrFail($id);

        try {
            if ($image->public_id) {
                Cloudinary::destroy($image->public_id);
            }
        } catch (Exception $e) {
            // Continuar con la eliminación en base de datos aunque falle en Cloudinary
        }

        $image->delete();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Imagen eliminada correctamente'], 200);
        }

        return redirect()->route('images.index')->with('success', 'Imagen eliminada correctamente');
    }

    /**
     * Subir imagen directamente para un Área específica (API helper).
     */
    public function uploadForArea(Request $request, Area $area)
    {
        $request->merge([
            'imageable_type' => Area::class,
            'imageable_id' => $area->id,
        ]);
        return $this->store($request);
    }

    /**
     * Subir imagen directamente para un Centro de Formación (API helper).
     */
    public function uploadForTrainingCenter(Request $request, TrainingCenter $trainingCenter)
    {
        $request->merge([
            'imageable_type' => TrainingCenter::class,
            'imageable_id' => $trainingCenter->id,
        ]);
        return $this->store($request);
    }

    /**
     * Subir imagen directamente para un Computador (API helper).
     */
    public function uploadForComputer(Request $request, Computer $computer)
    {
        $request->merge([
            'imageable_type' => Computer::class,
            'imageable_id' => $computer->id,
        ]);
        return $this->store($request);
    }

    /**
     * Resuelve el nombre del modelo a partir del string enviado.
     */
    private function resolveModelClass(string $type): ?string
    {
        if (isset($this->morphMap[$type])) {
            return $this->morphMap[$type];
        }

        if (class_exists($type) && in_array($type, [Area::class, TrainingCenter::class, Computer::class])) {
            return $type;
        }

        return null;
    }
}

