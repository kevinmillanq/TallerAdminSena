@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-images text-primary me-2"></i>Galería de Imágenes en Cloudinary</h2>
        <p class="text-muted mb-0">Imágenes polimórficas asociadas a Áreas, Centros de Formación y Computadores.</p>
    </div>
    <a href="{{ route('images.create') }}" class="btn btn-primary">
        <i class="bi bi-cloud-arrow-up-fill me-1"></i>Adjuntar Nueva Imagen
    </a>
</div>

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

@if($images->isEmpty())
    <div class="card border-0 shadow-sm p-5 text-center">
        <div class="text-muted">
            <i class="bi bi-image fs-1 d-block mb-3 text-secondary"></i>
            <h5>Aún no se han subido imágenes</h5>
            <p>Puedes adjuntar una imagen a un Área, Centro de Formación o Computador y subirla directamente a Cloudinary.</p>
            <a href="{{ route('images.create') }}" class="btn btn-outline-primary mt-2">
                <i class="bi bi-plus-circle me-1"></i>Subir la primera imagen
            </a>
        </div>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($images as $img)
            @php
                $typeBasename = class_basename($img->imageable_type);
                $badgeClass = match($typeBasename) {
                    'Area' => 'bg-primary',
                    'TrainingCenter' => 'bg-success',
                    'Computer' => 'bg-info text-dark',
                    default => 'bg-secondary'
                };
                $badgeLabel = match($typeBasename) {
                    'Area' => 'Área',
                    'TrainingCenter' => 'Centro de Formación',
                    'Computer' => 'Computador',
                    default => $typeBasename
                };
            @endphp
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="position-relative bg-light text-center" style="height: 220px; overflow: hidden;">
                        <a href="{{ $img->url }}" target="_blank" title="Ver en tamaño original">
                            <img src="{{ $img->url }}" class="card-img-top w-100 h-100" style="object-fit: cover;" alt="Imagen Cloudinary" loading="lazy">
                        </a>
                        <span class="position-absolute top-0 start-0 m-2 badge {{ $badgeClass }} shadow-sm">
                            {{ $badgeLabel }} #{{ $img->imageable_id }}
                        </span>
                    </div>

                    <div class="card-body">
                        <h6 class="card-title fw-bold mb-2">
                            @if($img->imageable)
                                @if($typeBasename === 'Area')
                                    <i class="bi bi-diagram-3 me-1 text-primary"></i>{{ $img->imageable->name }}
                                @elseif($typeBasename === 'TrainingCenter')
                                    <i class="bi bi-buildings me-1 text-success"></i>{{ $img->imageable->name }}
                                    <small class="d-block text-muted">Ubicación: {{ $img->imageable->location }}</small>
                                @elseif($typeBasename === 'Computer')
                                    <i class="bi bi-laptop me-1 text-info"></i>Equipo #{{ $img->imageable->number }}
                                    <small class="d-block text-muted">Marca: {{ $img->imageable->brand }}</small>
                                @endif
                            @else
                                <span class="text-muted fst-italic">Registro no encontrado (ID: {{ $img->imageable_id }})</span>
                            @endif
                        </h6>

                        <div class="small text-muted mb-2">
                            <div><strong>Public ID:</strong> <code>{{ $img->public_id ?? 'N/A' }}</code></div>
                            <div><strong>Subida:</strong> {{ $img->created_at ? $img->created_at->diffForHumans() : 'N/A' }}</div>
                        </div>

                        <div class="text-truncate small">
                            <a href="{{ $img->url }}" target="_blank" class="text-decoration-none">
                                <i class="bi bi-box-arrow-up-right me-1"></i>Ver en Cloudinary
                            </a>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top-0 d-flex justify-content-end pb-3">
                        <form action="{{ route('images.destroy', $img->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta imagen de Cloudinary y la base de datos?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash me-1"></i>Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection

