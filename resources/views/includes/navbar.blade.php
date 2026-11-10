<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="{{ route('images.index') }}">
        <i class="bi bi-cloud-check-fill me-1"></i>AdminSENA
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('images.create') ? 'active fw-bold' : '' }}" href="{{ route('images.create') }}">
              <i class="bi bi-cloud-arrow-up me-1"></i>Subir Imagen (Cloudinary)
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('images.index') ? 'active fw-bold' : '' }}" href="{{ route('images.index') }}">
              <i class="bi bi-images me-1"></i>Galería
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('areas.create') ? 'active fw-bold' : '' }}" href="{{ route('areas.create') }}">
              <i class="bi bi-diagram-3 me-1"></i>Crear Área
            </a>
          </li>
        </ul>
      </div>
    </div>
</nav>
