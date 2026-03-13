<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Genero;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;


class GeneroController extends Controller
   {
    public function destroy($id)
    {
        $genero = Genero::where('user_id', auth()->id())
            ->findOrFail($id);

        $this->transpasosGeneros($id);

        $genero->delete();

        return back()->with('message', 'Genero eliminado correctamente');
    }


    public function create()
    {
        return Inertia::render('books/form/generoFormulario', [
            'genero' => [
                'nombre' => ''

            ]
        ]);
    }

    public function store(Request $request)
    {
          $datos = $request->validate([
            'nombre' => [
                'required','string','min:3','max:50',
                Rule::unique('generos')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })
            ],
        ],
        [
            'nombre.unique' => 'Ya tienes un genero con ese nombre',
        ]);

        Genero::create([
            'nombre' => $datos['nombre'],
            'user_id' => auth()->id(),
        ]);

        return back()->with('message', 'Genero creada con éxito');
    }

    public function index()
    {
        $generos = Genero::all()->where('user_id', auth()->id())->values();

        return Inertia::render('books/generos', [
            'generos' => $generos
        ]);
    }

    protected function  transpasosGeneros($id)
    {
       
        $genero = Genero::where('user_id', auth()->id())->oldest()->value('id');
        Libro::where("genero_id",  $id)->update(['genero_id' => $genero]);

    }
}
