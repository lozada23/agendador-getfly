<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Mis Reservas
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow p-6 rounded">
                @forelse ($bookings as $booking)
                    <div class="border-b py-3">
                        <strong>Curso:</strong> {{ $booking->schedule->course->name ?? '---' }}<br>
                        <strong>Fecha:</strong> {{ $booking->schedule->date ?? '---' }}<br>
                        <strong>Estado:</strong> {{ ucfirst($booking->status) }}
                    </div>
                @empty
                    <p>No tienes reservas aún.</p>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>