@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-diagram-3 me-2"></i>Crear Nueva Área</h5>
            </div>
            <div class="card-body p-4">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('areas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nombre del Área:</label>
                        <input type="text" name="name" id="name" class="form-control" required placeholder="Ej: Telecomunicaciones, Sistemas, Agropecuaria..." value="{{ old('name') }}">
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Imagen (Opcional - Se subirá a Cloudinary):</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        <div class="form-text">Puedes adjuntar una imagen del área directamente aquí o hacerlo luego en la sección de imágenes.</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('images.create') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Ir a Subir Imagen
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save me-1"></i>Guardar Área
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

