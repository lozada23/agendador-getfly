<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Detalle de la Reserva
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow p-6 rounded">
                <p><strong>Código de Reserva:</strong> {{ $booking->booking_code }}</p>

                <p><strong>Curso:</strong> {{ $booking->schedule->course->name ?? '---' }}</p>
                <p><strong>Descripción:</strong> {{ $booking->schedule->course->description ?? '---' }}</p>

                <p><strong>Fecha:</strong> {{ $booking->schedule->date ?? '---' }}</p>
                <p><strong>Hora:</strong>
                    {{ \Carbon\Carbon::parse($booking->schedule->start_time)->format('H:i') }} -
                    {{ \Carbon\Carbon::parse($booking->schedule->end_time)->format('H:i') }}
                </p>

                <p><strong>Ubicación:</strong> {{ $booking->schedule->room->location->name ?? '---' }}</p>
                <p><strong>Aula / Simulador:</strong> {{ $booking->schedule->room->name ?? '---' }}</p>

                <p><strong>Estado:</strong>
                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </p>

                @if ($booking->status === 'confirmed')
                    <div class="mt-4">
                        <a href="{{ route('pilot.bookings.pdf', $booking->id) }}"
                           class="btn btn-sm btn-outline-primary">Descargar PDF</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>