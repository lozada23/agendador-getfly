<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Bienvenido, {{ Auth::user()->name }}</h3>

                    <div class="space-y-3">
                        <div>
                            <a href="{{ route('admin.courses.index') }}"
                               class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                               Gestionar Cursos
                            </a>
                        </div>
                        <div>
                            <a href="{{ route('admin.locations.index') }}"
                               class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                               Gestionar Ubicaciones
                            </a>
                        </div>
                        <div>
                            <a href="{{ route('admin.bookings.index') }}"
                               class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                               Consultar Reservas
                            </a>
                        </div>
                        <div>
                            <a href="{{ route('admin.export.index') }}"
                               class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                               Exportar Datos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
