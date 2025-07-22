<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

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

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'name' => 'required|min:3',
            'description' => 'nullable',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:course,simulator',
        ]);

        $course->update($request->all());

        return redirect()->route('admin.courses.index')->with('success', 'Curso actualizado correctamente.');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Curso eliminado correctamente.');
    }
}