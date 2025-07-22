<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Muestra el formulario para crear un curso.
     */
    public function create()
    {
        return view('admin.courses.create');
    }

    /**
     * Guarda un nuevo curso en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'description' => 'nullable',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:course,simulator',
        ]);

        Course::create($request->all());

        return redirect()->route('admin.courses.index')->with('success', 'Curso creado correctamente.');
    }

    /**
     * Lista los cursos.
     */
    public function index()
    {
        $courses = Course::all();
        return view('admin.courses.index', compact('courses'));
    }

    // Los siguientes métodos se completan más adelante
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}