<?php

namespace App\Http\Controllers;

use Inertia\Inertia;


use App\Http\Controllers\Controller;
use App\Models\Lectura;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LibroController extends Controller
{
    public function index(){
        $libros = Libro::all();

        return Inertia::render('books/libros', [
            'libros' => $libros
        ]);
    }

   public function buscar(Request $request)
{
    $libroQuery = Libro::query();

    $this->realizarBusqueda($libroQuery, $request);
    $libroQuery->with('lecturas');
    // Ejecutamos la paginación
    $libros = $libroQuery->paginate(10)->appends(request()->query());

    return Inertia::render('books/buscar', [
        'libros'  => $libros,
    ]);
}

    protected function realizarBusqueda($query, $request)
{
    $query->when($request->search, function($query, $search) {
        $query->where(function($q) use ($search) {
            $q->where('titulo', 'like', "%{$search}%")
              ->orWhere('autor', 'like', "%{$search}%")
              ->orWhere('editorial', 'like', "%{$search}%")
              ->orWhere('genero', 'like', "%{$search}%")
              ->orWhere('anio', 'like', "%{$search}%");
        });
    });
}

    public function editar($id){
        $libro = Libro::findOrFail($id);
        return Inertia::render('books/form/libroFormulario', ['libro' => $libro,
        'isUpdating' => true]);
    }

    public function update(Request $request, $id)
{
    $libro = Libro::findOrFail($id);

    $datos = $request->validate([
        'titulo' => 'required|string|max:255',
        'autor'  => 'required|string|max:255',
        'anio'   => 'required|integer|digits:4',
        'editorial'=>'string|required',
        'paginas'=>'integer|required',
        'genero'=>'string|required',
        'portada'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
    ]);

    //Logica de la imagen
    if($request->hasFile('portada')){
        if($libro->portada){
            Storage::disk('public')->delete($libro->portada);
        }

        $datos['portada'] = $request->file('portada')->store('portada','public');
    } else {
        unset($datos['portada']);
    }


    $libro->update($datos);

    return redirect()->route('books.buscar')->with('message', 'Libro actualizado con éxito');
}

public function destroy($id)
{
    $libro = Libro::findOrFail($id);

    $libro->delete();

    return redirect()->route('books.buscar')->with('message', 'Libro eliminado correctamente');
}


public function create()
{
    return Inertia::render('books/form/libroFormulario', [
        'libro' => [
            'titulo' => '',
            'autor' => '',
            'anio' => '',
            'editorial' => '',
            'paginas' => '',
            'genero' => '',
            'portada' => null,
            

        ],
        'isUpdating' => false
    ]);
}

public function store(Request $request)
{
    $datos = $request->validate([
        'titulo'    => 'required|string|max:255',
        'autor'     => 'required|string|max:255',
        'anio'      => 'required|integer|digits:4',
        'editorial' => 'required|string',
        'paginas'   => 'required|integer',
        'genero'    => 'required|string',
        // Cambiamos 'string' por validación de imagen
        'portada'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048' 
    ]);

    // Lógica para guardar la imagen física
    if ($request->hasFile('portada')) {
        // Guardamos en storage/app/public/portada y obtenemos la ruta
        $datos['portada'] = $request->file('portada')->store('portada', 'public');
    }

    // Creamos el libro (ahora 'portada' ya es un string con la ruta)
    $libro = Libro::create($datos);

    // Creamos la lectura asociada
    Lectura::create([
        'id_libro'       => $libro->id,
        'estado'         => "Pendiente", 
        'puntuacion'     => 0,
        'final_lectura'  => null,
        'inicio_lectura' => now()->format('Y-m-d'), 
        'tiempo_lectura' => 0,
        'paginas_diarias'=> 0,
        'reseña'         => null,
    ]);

    return redirect()->route('books.buscar')->with('message', 'Libro creado con éxito');
}

}