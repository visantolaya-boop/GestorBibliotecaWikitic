<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Libro;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;


class UbicacionController extends Controller
{
    public function destroy($id)
    {
        $ubicacion = Ubicacion::where('user_id', auth()->id())
            ->findOrFail($id);

        $this->transpasosLibros($id);

        $ubicacion->delete();

        return back()->with('message', 'Ubicación eliminada correctamente');
    }


    public function create()
    {
        return Inertia::render('books/form/ubicacionFormulario', [
            'ubicacion' => [
                'nombre' => ''

            ]
        ]);
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => [
                'required','string','min:3','max:50',
                Rule::unique('ubicacion')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })
            ],
        ],
        [
            'nombre.unique' => 'Ya tienes una ubicación con ese nombre',
        ]);

        Ubicacion::create([
            'nombre' => $datos['nombre'],
            'user_id' => auth()->id(),
        ]);

        return back()->with('message', 'Ubicación creada con éxito');
    }

    public function index()
    {
        $ubicaciones = Ubicacion::all()->where('user_id', auth()->id())->values();

        return Inertia::render('books/ubicaciones', [
            'ubicaciones' => $ubicaciones
        ]);
    }

    protected function  transpasosLibros($id)
    {

        $ubicacion = Ubicacion::where('user_id', auth()->id())->oldest()->value('id');
        Libro::where("ubicacion_id",  $id)->update(['ubicacion_id' => $ubicacion]);
    }
}
