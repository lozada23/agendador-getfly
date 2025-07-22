<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Registrar Estudiante
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded p-6">

                <form method="POST" action="{{ route('company.students.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-control">
                        @error('name')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="document_number" class="form-label">Número de Documento</label>
                        <input type="text" id="document_number" name="document_number" value="{{ old('document_number') }}" required class="form-control">
                        @error('document_number')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control">
                        @error('email')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="form-control">
                        @error('phone')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>