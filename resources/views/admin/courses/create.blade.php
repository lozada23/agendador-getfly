<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Curso
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <strong>Ups!</strong> Hay algunos errores con tu envío:
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.courses.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block font-medium text-gray-700">Nombre del Curso</label>
                        <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2 mt-1" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block font-medium text-gray-700">Descripción</label>
                        <textarea name="description" id="description" rows="3" class="w-full border rounded px-3 py-2 mt-1">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="duration" class="block font-medium text-gray-700">Duración (minutos)</label>
                        <input type="number" name="duration" id="duration" class="w-full border rounded px-3 py-2 mt-1" value="{{ old('duration') }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block font-medium text-gray-700">Precio</label>
                        <input type="number" step="0.01" name="price" id="price" class="w-full border rounded px-3 py-2 mt-1" value="{{ old('price') }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="type" class="block font-medium text-gray-700">Tipo</label>
                        <select name="type" id="type" class="w-full border rounded px-3 py-2 mt-1" required>
                            <option value="">Seleccione</option>
                            <option value="course" {{ old('type') === 'course' ? 'selected' : '' }}>Curso</option>
                            <option value="simulator" {{ old('type') === 'simulator' ? 'selected' : '' }}>Simulador</option>
                        </select>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Guardar Curso
                        </button>
                        <a href="{{ route('admin.courses.index') }}" class="ml-4 text-gray-600 hover:text-gray-900 underline">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>