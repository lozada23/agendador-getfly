<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Mostrar página de pago para una reserva
     */
    public function show(Booking $booking)
    {
        // Verificar que la reserva pertenezca al usuario autenticado
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Verificar que la reserva esté en estado pendiente
        if ($booking->status !== 'pending') {
            return redirect()->route('client.bookings.show', $booking)
                ->withErrors(['message' => 'Esta reserva ya ha sido procesada.']);
        }
        
        // Obtener o crear el registro de pago
        $payment = $booking->payment ?? $this->paymentService->createPayment($booking);
        
        // Generar URL de pago para ePayco
        $paymentUrl = $this->paymentService->generatePaymentUrl($payment);
        
        return view('client.payments.show', compact('booking', 'payment', 'paymentUrl'));
    }

    /**
     * Procesar el pago de una reserva
     */
    public function process(Request $request, Booking $booking)
    {
        // Verificar que la reserva pertenezca al usuario autenticado
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Verificar que la reserva esté en estado pendiente
        if ($booking->status !== 'pending') {
            return redirect()->route('client.bookings.show', $booking)
                ->withErrors(['message' => 'Esta reserva ya ha sido procesada.']);
        }
        
        // Obtener o crear el registro de pago
        $payment = $booking->payment ?? $this->paymentService->createPayment($booking);
        
        // Redireccionar a la pasarela de pago de ePayco
        $paymentUrl = $this->paymentService->generatePaymentUrl($payment);
        
        return redirect()->away($paymentUrl);
    }

    /**
     * Manejar la respuesta exitosa de ePayco
     */
    public function success(Request $request, Booking $booking)
    {
        // Verificar que la reserva pertenezca al usuario autenticado
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        // La confirmación real del pago se hace a través del webhook
        // Aquí solo mostramos una página de éxito al usuario
        
        return view('client.payments.success', compact('booking'));
    }

    /**
     * Manejar la cancelación del pago
     */
    public function cancel(Request $request, Booking $booking)
    {
        // Verificar que la reserva pertenezca al usuario autenticado
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('client.payments.cancel', compact('booking'));
    }

    /**
     * Procesar webhook de ePayco
     */
    public function handleWebhook(Request $request)
    {
        // Procesar la respuesta del webhook
        try {
            $payment = $this->paymentService->processWebhookResponse($request->all());
            
            // Responder con éxito
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            // Registrar el error
            \Log::error('Error procesando webhook de ePayco: ' . $e->getMessage());
            
            // Responder con error
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }
}
