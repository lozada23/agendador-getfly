<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Mostrar listado de cursos y simuladores disponibles
     */
    public function index(Request $request)
    {
        $query = Course::query();
        
        // Filtrar por tipo (curso/simulador)
        if ($request->has('type') && in_array($request->type, ['course', 'simulator'])) {
            $query->where('type', $request->type);
        }
        
        // Filtrar por sede
        if ($request->has('location_id')) {
            $query->whereHas('schedules.room', function($q) use ($request) {
                $q->where('location_id', $request->location_id);
            });
        }
        
        // Ordenar por nombre
        $query->orderBy('name');
        
        // Paginar resultados
        $courses = $query->paginate(12);
        
        return view('pilot.courses.index', compact('courses'));
    }

    /**
     * Mostrar detalles de un curso o simulador
     */
    public function show(Course $course)
    {
        // Cargar horarios disponibles
        $schedules = $course->schedules()
                          ->where('date', '>=', now()->format('Y-m-d'))
                          ->where('available_slots', '>', 0)
                          ->orderBy('date')
                          ->orderBy('start_time')
                          ->get();
        
        return view('pilot.courses.show', compact('course', 'schedules'));
    }
}
