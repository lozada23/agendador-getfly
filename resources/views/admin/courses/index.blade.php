<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista de Cursos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.courses.create') }}"
                   class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    ➕ Nuevo Curso
                </a>
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-x-auto">
                @if ($courses->count())
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-100 text-gray-700 font-semibold">
                            <tr>
                                <th class="px-4 py-3">Nombre</th>
                                <th class="px-4 py-3">Duración</th>
                                <th class="px-4 py-3">Precio</th>
                                <th class="px-4 py-3">Tipo</th>
                                <th class="px-4 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $course)
                                <tr class="border-t">
                                    <td class="px-4 py-2">{{ $course->name }}</td>
                                    <td class="px-4 py-2">{{ $course->duration }} min</td>
                                    <td class="px-4 py-2">${{ number_format($course->price, 2) }}</td>
                                    <td class="px-4 py-2">{{ $course->type === 'course' ? 'Curso' : 'Simulador' }}</td>
                                    <td class="px-4 py-2 text-right">
                                        <a href="{{ route('admin.courses.edit', $course) }}"
                                           class="text-blue-600 hover:underline">Editar</a>
                                        <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este curso?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ml-2 text-red-600 hover:underline">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-6 text-gray-600">
                        No hay cursos registrados aún.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>