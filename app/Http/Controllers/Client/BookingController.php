<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\PdfGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    protected $bookingService;
    protected $pdfService;

    public function __construct(BookingService $bookingService, PdfGeneratorService $pdfService)
    {
        $this->bookingService = $bookingService;
        $this->pdfService = $pdfService;
    }

    /**
     * Mostrar listado de reservas del usuario
     */
    public function index()
    {
        $bookings = Auth::user()
            ->bookings()
            ->with('schedule.course') // Cargar relaciones necesarias
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pilot.bookings.index', compact('bookings'));
    }

    /**
     * Mostrar formulario para crear una nueva reserva
     */
    public function create(Course $course)
    {
        $schedules = Schedule::where('course_id', $course->id)
            ->where('date', '>=', now()->format('Y-m-d'))
            ->where('available_slots', '>', 0)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('pilot.bookings.create', compact('course', 'schedules'));
    }

    /**
     * Almacenar una nueva reserva
     */
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
        ]);

        try {
            $user = Auth::user();
            $schedule = Schedule::findOrFail($request->schedule_id);

            // Crear la reserva usando el servicio
            $booking = $this->bookingService->createBooking($user, $schedule);

            return redirect()->route('pilot.payment.show', $booking);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Mostrar detalles de una reserva
     */
    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('pilot.bookings.show', compact('booking'));
    }


    /**
     * Descargar PDF de confirmación de reserva
     */
    public function downloadPdf(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'confirmed') {
            return back()->withErrors(['message' => 'Solo se pueden descargar PDFs para reservas confirmadas.']);
        }

        try {
            $pdfPath = $this->pdfService->getBookingPdfPath($booking);

            if (!$pdfPath || !Storage::exists($pdfPath)) {
                return back()->withErrors(['message' => 'No se pudo generar el PDF de confirmación.']);
            }

            return Storage::download($pdfPath, 'reserva_' . $booking->booking_code . '.pdf');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }
}
