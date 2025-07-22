<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Curso
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('admin.courses.update', $course->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="block font-semibold">Nombre</label>
                        <input type="text" id="name" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name', $course->name) }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block font-semibold">Descripción</label>
                        <textarea id="description" name="description" class="w-full border rounded px-3 py-2">{{ old('description', $course->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="duration" class="block font-semibold">Duración (minutos)</label>
                        <input type="number" id="duration" name="duration" class="w-full border rounded px-3 py-2" value="{{ old('duration', $course->duration) }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block font-semibold">Precio</label>
                        <input type="number" id="price" name="price" step="0.01" class="w-full border rounded px-3 py-2" value="{{ old('price', $course->price) }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="type" class="block font-semibold">Tipo</label>
                        <select name="type" id="type" class="w-full border rounded px-3 py-2" required>
                            <option value="">Seleccione</option>
                            <option value="course" {{ old('type', $course->type) === 'course' ? 'selected' : '' }}>Curso</option>
                            <option value="simulator" {{ old('type', $course->type) === 'simulator' ? 'selected' : '' }}>Simulador</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('admin.courses.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded mr-2">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Actualizar Curso
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>