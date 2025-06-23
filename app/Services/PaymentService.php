<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;

class PaymentService
{
    /**
     * Crear una nueva transacción de pago
     *
     * @param Booking $booking
     * @return Payment
     */
    public function createPayment(Booking $booking): Payment
    {
        // Verificar que la reserva esté en estado pendiente
        if ($booking->status !== 'pending') {
            throw new \Exception('Solo se pueden procesar pagos para reservas pendientes.');
        }

        // Crear el registro de pago
        $payment = new Payment();
        $payment->booking_id = $booking->id;
        $payment->amount = $booking->schedule->course->price;
        $payment->status = 'pending';
        $payment->save();

        return $payment;
    }

    /**
     * Generar URL de pago para ePayco
     *
     * @param Payment $payment
     * @return string
     */
    public function generatePaymentUrl(Payment $payment): string
    {
        // Datos para la integración con ePayco
        $booking = $payment->booking;
        $user = $booking->user;
        $course = $booking->schedule->course;
        
        // En un entorno real, estos datos vendrían de la configuración
        $epaycoPublicKey = env('EPAYCO_PUBLIC_KEY', 'test_public_key');
        $epaycoTest = env('EPAYCO_TEST', true);
        
        // URL de retorno después del pago
        $returnUrl = route('client.payment.success', ['booking' => $booking->id]);
        
        // En un entorno real, se construiría la URL con los parámetros necesarios
        // para la integración con ePayco. Aquí simulamos la URL.
        $paymentUrl = "https://secure.epayco.co/payment.html?p_cust_id_cliente={$epaycoPublicKey}" .
                     "&p_key={$epaycoPublicKey}" .
                     "&p_id_invoice={$payment->id}" .
                     "&p_description=" . urlencode("Reserva {$booking->booking_code} - {$course->name}") .
                     "&p_amount={$payment->amount}" .
                     "&p_currency_code=COP" .
                     "&p_signature=" . md5("llave_secreta~{$epaycoPublicKey}~{$payment->id}~{$payment->amount}~COP") .
                     "&p_test_request=" . ($epaycoTest ? 'TRUE' : 'FALSE') .
                     "&p_url_response=" . urlencode($returnUrl) .
                     "&p_url_confirmation=" . urlencode(route('payment.webhook')) .
                     "&p_extra1=" . urlencode($booking->booking_code) .
                     "&p_extra2=" . urlencode($user->email);
        
        return $paymentUrl;
    }

    /**
     * Procesar respuesta de webhook de ePayco
     *
     * @param array $data
     * @return Payment
     */
    public function processWebhookResponse(array $data): Payment
    {
        // Validar la firma para asegurar que la respuesta viene de ePayco
        // En un entorno real, se verificaría la firma con la llave secreta
        
        // Buscar el pago por el ID de transacción
        $payment = Payment::where('id', $data['x_id_invoice'] ?? null)->first();
        
        if (!$payment) {
            throw new \Exception('Pago no encontrado.');
        }
        
        // Actualizar el estado del pago según la respuesta
        $status = $data['x_response'] ?? '';
        
        if (strtolower($status) === 'approved' || strtolower($status) === 'aceptada') {
            $payment->status = 'approved';
            $payment->transaction_id = $data['x_transaction_id'] ?? null;
            $payment->payment_method = $data['x_franchise'] ?? null;
            $payment->save();
            
            // Confirmar la reserva
            $bookingService = new BookingService();
            $bookingService->confirmBooking($payment->booking);
        } elseif (strtolower($status) === 'rejected' || strtolower($status) === 'rechazada') {
            $payment->status = 'rejected';
            $payment->save();
        }
        
        return $payment;
    }
}
