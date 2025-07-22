<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Lista de Cursos
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-md rounded">
                @forelse ($courses as $course)
                    <div class="border-b py-2">
                        <strong>{{ $course->name }}</strong>
                        <p>{{ $course->description }}</p>
                    </div>
                @empty
                    <p>No hay cursos disponibles.</p>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
