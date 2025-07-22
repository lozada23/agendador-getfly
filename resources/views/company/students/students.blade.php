<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Estudiantes Registrados
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded p-6">
                @if ($students->isEmpty())
                    <p class="text-gray-600">No hay estudiantes registrados aún.</p>
                @else
                    <table class="table-auto w-full border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Nombre</th>
                                <th class="px-4 py-2 text-left">Documento</th>
                                <th class="px-4 py-2 text-left">Correo</th>
                                <th class="px-4 py-2 text-left">Teléfono</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr class="border-t">
                                    <td class="px-4 py-2">{{ $student->name }}</td>
                                    <td class="px-4 py-2">{{ $student->document_number }}</td>
                                    <td class="px-4 py-2">{{ $student->email ?? '—' }}</td>
                                    <td class="px-4 py-2">{{ $student->phone ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>