<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfGeneratorService
{
    /**
     * Generar PDF de confirmación de reserva
     *
     * @param Booking $booking
     * @return string Ruta al archivo PDF generado
     */
    public function generateBookingConfirmation(Booking $booking): string
    {
        // Verificar que la reserva esté confirmada
        if ($booking->status !== 'confirmed') {
            throw new \Exception('Solo se pueden generar PDFs para reservas confirmadas.');
        }

        // Preparar datos para la vista
        $data = [
            'booking' => $booking,
            'user' => $booking->user,
            'schedule' => $booking->schedule,
            'course' => $booking->schedule->course,
            'location' => $booking->schedule->room->location,
            'room' => $booking->schedule->room,
            'payment' => $booking->payment,
            'generated_at' => now(),
        ];

        // Generar el PDF
        $pdf = PDF::loadView('pdf.booking_confirmation', $data);
        
        // Definir nombre del archivo
        $filename = 'booking_' . $booking->booking_code . '.pdf';
        
        // Guardar el PDF en el almacenamiento
        $path = 'pdfs/' . $filename;
        Storage::put($path, $pdf->output());
        
        // Actualizar la ruta del PDF en la reserva
        $booking->pdf_path = $path;
        $booking->save();
        
        return $path;
    }
    
    /**
     * Obtener la ruta completa al PDF de una reserva
     *
     * @param Booking $booking
     * @return string|null
     */
    public function getBookingPdfPath(Booking $booking): ?string
    {
        // Si ya existe un PDF generado, devolver su ruta
        if ($booking->pdf_path && Storage::exists($booking->pdf_path)) {
            return $booking->pdf_path;
        }
        
        // Si la reserva está confirmada pero no tiene PDF, generarlo
        if ($booking->status === 'confirmed') {
            return $this->generateBookingConfirmation($booking);
        }
        
        return null;
    }
}
