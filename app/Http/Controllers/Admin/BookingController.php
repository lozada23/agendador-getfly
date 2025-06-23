<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\PdfGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    protected $pdfService;

    public function __construct(PdfGeneratorService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Mostrar listado de reservas
     */
    public function index(Request $request)
    {
        $query = Booking::query();
        
        // Filtrar por fecha
        if ($request->has('date_from')) {
            $query->whereHas('schedule', function($q) use ($request) {
                $q->where('date', '>=', $request->date_from);
            });
        }
        
        if ($request->has('date_to')) {
            $query->whereHas('schedule', function($q) use ($request) {
                $q->where('date', '<=', $request->date_to);
            });
        }
        
        // Filtrar por curso
        if ($request->has('course_id')) {
            $query->whereHas('schedule', function($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }
        
        // Filtrar por sede
        if ($request->has('location_id')) {
            $query->whereHas('schedule.room', function($q) use ($request) {
                $q->where('location_id', $request->location_id);
            });
        }
        
        // Filtrar por estado
        if ($request->has('status') && in_array($request->status, ['pending', 'confirmed', 'cancelled'])) {
            $query->where('status', $request->status);
        }
        
        // Filtrar por cédula del cliente
        if ($request->has('document_number')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('document_number', 'like', '%' . $request->document_number . '%');
            });
        }
        
        // Ordenar por fecha de creación (más reciente primero)
        $query->orderBy('created_at', 'desc');
        
        // Paginar resultados
        $bookings = $query->paginate(20);
        
        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Mostrar detalles de una reserva
     */
    public function show(Booking $booking)
    {
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Descargar PDF de confirmación de reserva
     */
    public function downloadPdf(Booking $booking)
    {
        // Verificar que la reserva esté confirmada
        if ($booking->status !== 'confirmed') {
            return back()->withErrors(['message' => 'Solo se pueden descargar PDFs para reservas confirmadas.']);
        }
        
        try {
            // Obtener la ruta del PDF
            $pdfPath = $this->pdfService->getBookingPdfPath($booking);
            
            if (!$pdfPath || !Storage::exists($pdfPath)) {
                return back()->withErrors(['message' => 'No se pudo generar el PDF de confirmación.']);
            }
            
            // Descargar el archivo
            return Storage::download($pdfPath, 'reserva_' . $booking->booking_code . '.pdf');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }
}
