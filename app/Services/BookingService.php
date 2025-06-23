<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Str;

class BookingService
{
    /**
     * Verificar disponibilidad de un horario
     *
     * @param Schedule $schedule
     * @return bool
     */
    public function checkAvailability(Schedule $schedule): bool
    {
        return $schedule->available_slots > 0;
    }

    /**
     * Crear una nueva reserva
     *
     * @param User $user
     * @param Schedule $schedule
     * @return Booking
     */
    public function createBooking(User $user, Schedule $schedule): Booking
    {
        // Verificar disponibilidad
        if (!$this->checkAvailability($schedule)) {
            throw new \Exception('No hay cupos disponibles para este horario.');
        }

        // Generar código único de reserva
        $bookingCode = $this->generateBookingCode();

        // Crear la reserva
        $booking = new Booking();
        $booking->user_id = $user->id;
        $booking->schedule_id = $schedule->id;
        $booking->booking_code = $bookingCode;
        $booking->status = 'pending';
        $booking->save();

        // Actualizar cupos disponibles
        $schedule->available_slots -= 1;
        $schedule->save();

        return $booking;
    }

    /**
     * Confirmar una reserva después del pago
     *
     * @param Booking $booking
     * @return Booking
     */
    public function confirmBooking(Booking $booking): Booking
    {
        $booking->status = 'confirmed';
        $booking->save();

        return $booking;
    }

    /**
     * Cancelar una reserva
     *
     * @param Booking $booking
     * @return Booking
     */
    public function cancelBooking(Booking $booking): Booking
    {
        // Solo se pueden cancelar reservas pendientes o confirmadas
        if ($booking->status === 'cancelled') {
            throw new \Exception('Esta reserva ya ha sido cancelada.');
        }

        $booking->status = 'cancelled';
        $booking->save();

        // Restaurar cupo disponible
        $schedule = $booking->schedule;
        $schedule->available_slots += 1;
        $schedule->save();

        return $booking;
    }

    /**
     * Generar un código único para la reserva
     *
     * @return string
     */
    private function generateBookingCode(): string
    {
        $prefix = 'GF-';
        $uniqueCode = $prefix . strtoupper(Str::random(8));

        // Verificar que el código no exista ya
        while (Booking::where('booking_code', $uniqueCode)->exists()) {
            $uniqueCode = $prefix . strtoupper(Str::random(8));
        }

        return $uniqueCode;
    }
}
