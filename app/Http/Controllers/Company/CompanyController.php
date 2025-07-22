<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyStudent;
use App\Models\Booking;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Mostrar listado de estudiantes registrados por la empresa.
     */
    public function index()
    {
        $students = CompanyStudent::where('company_id', auth()->id())->get();
        return view('company.students.index', compact('students'));
    }

    /**
     * Mostrar formulario para registrar nuevo estudiante.
     */
    public function create()
    {
        return view('company.students.create');
    }

    /**
     * Guardar nuevo estudiante.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'document_number' => 'required|unique:company_students',
            'email' => 'nullable|email',
            'phone' => 'nullable|min:10',
        ]);

        CompanyStudent::create([
            'company_id' => auth()->id(),
            'name' => $request->name,
            'document_number' => $request->document_number,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('company.students.index')->with('success', 'Estudiante registrado');
    }

    /**
     * Mostrar reservas asociadas a los estudiantes de esta empresa.
     */
    public function bookings()
    {
        $students = CompanyStudent::where('company_id', auth()->id())->pluck('document_number');
        $bookings = Booking::whereIn('student_document', $students)->with(['schedule.course'])->get();

        return view('company.bookings', compact('bookings'));
    }
}
