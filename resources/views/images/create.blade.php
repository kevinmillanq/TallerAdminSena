@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-cloud-arrow-up me-2"></i>Adjuntar y Subir Imagen a Cloudinary</h5>
                <a href="{{ route('images.index') }}" class="btn btn-sm btn-light">
                    <i class="bi bi-images me-1"></i>Ver Galería
                </a>
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

                <p class="text-muted small">
                    De acuerdo al modelo relacional de AdminSENA, una imagen puede adjuntarse de forma polimórfica a un <strong>Área</strong>, un <strong>Centro de Formación</strong> o un <strong>Computador</strong>.
                </p>

                <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf

                    <!-- 1. Selección de Entidad -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">1. Seleccione a qué entidad pertenece la imagen:</label>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="imageable_type" id="type_area" value="Area" autocomplete="off" {{ old('imageable_type', 'Area') == 'Area' ? 'checked' : '' }} onchange="onTypeChange('Area')">
                                <label class="btn btn-outline-primary w-100 py-3 text-center" for="type_area">
                                    <i class="bi bi-diagram-3 fs-3 d-block mb-1"></i>
                                    <strong>Área</strong>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="imageable_type" id="type_center" value="TrainingCenter" autocomplete="off" {{ old('imageable_type') == 'TrainingCenter' ? 'checked' : '' }} onchange="onTypeChange('TrainingCenter')">
                                <label class="btn btn-outline-success w-100 py-3 text-center" for="type_center">
                                    <i class="bi bi-buildings fs-3 d-block mb-1"></i>
                                    <strong>Centro de Formación</strong>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="imageable_type" id="type_computer" value="Computer" autocomplete="off" {{ old('imageable_type') == 'Computer' ? 'checked' : '' }} onchange="onTypeChange('Computer')">
                                <label class="btn btn-outline-info w-100 py-3 text-center" for="type_computer">
                                    <i class="bi bi-laptop fs-3 d-block mb-1"></i>
                                    <strong>Computador</strong>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Selección del Registro Específico -->
                    <div class="mb-4">
                        <label for="imageable_id" class="form-label fw-bold">2. Seleccione el registro específico:</label>
                        
                        <!-- Select Áreas -->
                        <div id="select_area_wrapper" class="entity-select">
                            <select class="form-select" id="select_area" onchange="syncId(this.value)">
                                <option value="">-- Seleccionar Área --</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ old('imageable_id') == $area->id ? 'selected' : '' }}>
                                        #{{ $area->id }} - {{ $area->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($areas->isEmpty())
                                <small class="text-danger d-block mt-1">No hay áreas registradas. Puedes registrar una o digitar el ID manualmente abajo.</small>
                            @endif
                        </div>

                        <!-- Select Centros de Formación -->
                        <div id="select_center_wrapper" class="entity-select d-none">
                            <select class="form-select" id="select_center" onchange="syncId(this.value)">
                                <option value="">-- Seleccionar Centro de Formación --</option>
                                @foreach($trainingCenters as $center)
                                    <option value="{{ $center->id }}" {{ old('imageable_id') == $center->id ? 'selected' : '' }}>
                                        #{{ $center->id }} - {{ $center->name }} ({{ $center->location }})
                                    </option>
                                @endforeach
                            </select>
                            @if($trainingCenters->isEmpty())
                                <small class="text-danger d-block mt-1">No hay centros de formación registrados. Puedes digitar el ID manualmente abajo.</small>
                            @endif
                        </div>

                        <!-- Select Computadores -->
                        <div id="select_computer_wrapper" class="entity-select d-none">
                            <select class="form-select" id="select_computer" onchange="syncId(this.value)">
                                <option value="">-- Seleccionar Computador --</option>
                                @foreach($computers as $computer)
                                    <option value="{{ $computer->id }}" {{ old('imageable_id') == $computer->id ? 'selected' : '' }}>
                                        #{{ $computer->id }} - Equipo: {{ $computer->number }} (Marca: {{ $computer->brand }})
                                    </option>
                                @endforeach
                            </select>
                            @if($computers->isEmpty())
                                <small class="text-danger d-block mt-1">No hay computadores registrados. Puedes digitar el ID manualmente abajo.</small>
                            @endif
                        </div>

                        <!-- Input oculto o de respaldo para imageable_id -->
                        <div class="mt-2">
                            <label class="form-label small text-muted">ID seleccionado:</label>
                            <input type="number" name="imageable_id" id="imageable_id" class="form-control form-control-sm" value="{{ old('imageable_id') }}" required placeholder="ID de la entidad">
                        </div>
                    </div>

                    <!-- 3. Archivo de Imagen -->
                    <div class="mb-4">
                        <label for="image" class="form-label fw-bold">3. Seleccione el archivo de imagen:</label>
                        <input class="form-control" type="file" id="image" name="image" accept="image/*" required onchange="previewImage(event)">
                        <div class="form-text">Formatos permitidos: JPG, PNG, WEBP, GIF, SVG. Máximo 5MB.</div>

                        <!-- Vista Previa -->
                        <div id="previewContainer" class="mt-3 text-center p-3 border rounded bg-light d-none">
                            <p class="small text-muted mb-2">Vista previa de la imagen seleccionada:</p>
                            <img id="imagePreview" src="" alt="Vista previa" class="img-fluid rounded shadow-sm" style="max-height: 250px;">
                        </div>
                    </div>

                    <!-- Botón de Envío -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                            <i class="bi bi-cloud-arrow-up-fill me-2"></i>Subir a Cloudinary y Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function onTypeChange(type) {
        document.querySelectorAll('.entity-select').forEach(el => el.classList.add('d-none'));

        let selectedId = '';
        if (type === 'Area') {
            const el = document.getElementById('select_area_wrapper');
            if (el) el.classList.remove('d-none');
            selectedId = document.getElementById('select_area').value;
        } else if (type === 'TrainingCenter') {
            const el = document.getElementById('select_center_wrapper');
            if (el) el.classList.remove('d-none');
            selectedId = document.getElementById('select_center').value;
        } else if (type === 'Computer') {
            const el = document.getElementById('select_computer_wrapper');
            if (el) el.classList.remove('d-none');
            selectedId = document.getElementById('select_computer').value;
        }
        syncId(selectedId);
    }

    function syncId(val) {
        document.getElementById('imageable_id').value = val || '';
    }

    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('previewContainer');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            container.classList.add('d-none');
        }
    }

    // Inicializar al cargar
    document.addEventListener('DOMContentLoaded', function() {
        const checkedType = document.querySelector('input[name="imageable_type"]:checked');
        if (checkedType) {
            onTypeChange(checkedType.value);
        }

        const form = document.getElementById('uploadForm');
        form.addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Subiendo a Cloudinary...';
        });
    });
</script>
@endsection

