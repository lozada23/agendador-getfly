<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card bg-white shadow-sm p-6">
                <form method="POST" action="{{ route('pilot.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div class="mb-4">
                        <label for="name" class="form-label">Nombre</label>
                        <input id="name" type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                            class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Correo electrónico (solo lectura) -->
                    <div class="mb-4">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input id="email" type="email" name="email" value="{{ Auth::user()->email }}" class="form-control" readonly>
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-4">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                            class="form-control @error('phone') is-invalid @enderror">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nueva contraseña -->
                    <div class="mb-4">
                        <label for="password" class="form-label">Nueva contraseña</label>
                        <input id="password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirmar nueva contraseña -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirmar nueva contraseña</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control">
                    </div>

                    <!-- Contraseña actual -->
                    <div class="mb-4">
                        <label for="current_password" class="form-label">Contraseña actual (requerida para cambiar contraseña)</label>
                        <input id="current_password" type="password" name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botón -->
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
