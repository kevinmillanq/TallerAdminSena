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


//vista formulario

    public function create()
    {
        return view('areas.create');
    }


    // post recibe datos formulario y crea registro

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|max:255',
    ]);
    $area = Area::create($request->all());
    return response()->json($area, 201);
    }

    // UPDATE recibe nuevos datos y actualiza existentes

    public function update(Request $request, Area $area)
    {
        //valida campo
        $request->validate([
            'name' => 'required|max:255',
        ]);

        //actualiza con los datos nuevos
        $area->update($request->all());


        //devulve el resgistro ya cambiado
        return response()->json($area);
    }

    
    //metodo show
    public function show($id)
    {
    return response()->json(Area::findOrFail($id));
    }


}