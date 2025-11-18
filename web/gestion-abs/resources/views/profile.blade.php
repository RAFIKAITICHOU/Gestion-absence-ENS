@extends('layouts.' . $menu)
@section('breadcrumb', 'Mon Profil')
@section('title', 'Mon Profil')

@section('content')
<!-- Toast Bootstrap -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
  <div id="photoToast" class="toast align-items-center text-white bg-success border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        📸 Photo mise à jour avec succès !
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
    </div>
  </div>
</div>

@php use Illuminate\Support\Str; @endphp

<div class="container-fluid py-4">
  <!-- En-tête stylisé -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body bg-gradient-primary text-white py-4 px-4 rounded-4">
          <div class="d-flex align-items-center">
            <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
              <i class="bi bi-person-circle fs-2 text-white"></i>
            </div>
            <div>
              <h1 class="mb-0 fw-bold">Mon Profil</h1>
              <p class="mb-0 opacity-75">Gérez vos informations personnelles et votre mot de passe</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Messages de succès/erreur -->
  @if(session('success'))
  <div class="row mb-4">
    <div class="col-12">
      <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm" role="alert">
        <div class="d-flex align-items-center">
          <i class="bi bi-check-circle-fill me-2 fs-5"></i>
          <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
      </div>
    </div>
  </div>
  @endif
  @if($errors->any())
  <div class="row mb-4">
    <div class="col-12">
      <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm" role="alert">
        <div class="d-flex align-items-start">
          <i class="bi bi-exclamation-circle-fill me-2 fs-5 flex-shrink-0 mt-1"></i>
          <ul class="mb-0">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
      </div>
    </div>
  </div>
  @endif

  <div class="row">
    <!-- Informations personnelles -->
    <div class="col-lg-7 mb-4">
      <div class="card shadow-lg border-0 rounded-4 h-100">
        <div class="card-header bg-primary text-white border-0 rounded-top-4 py-4">
          <h5 class="mb-0 fw-bold"><i class="bi bi-person-badge me-2"></i>Informations personnelles</h5>
        </div>
        <div class="card-body p-4">
          @php
          $isDefault = Str::startsWith($user->photo, 'images/');
          $basePath = $isDefault ? asset($user->photo) : asset('storage/' . $user->photo);
          $photoPath = $basePath . '?v=' . time();
          @endphp
          <!-- Photo cliquable avec effet -->
          <div class="d-flex flex-column align-items-center mb-5">
            <div class="position-relative">
              <img src="{{ $photoPath }}" alt="Photo de profil"
                class="rounded-circle shadow-lg mb-3 photo-hover"
                style="width: 150px; height: 150px; object-fit: cover; cursor: pointer; border: 4px solid #fff;"
                id="clickablePhoto">
              <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2 shadow photo-edit-icon">
                <i class="bi bi-camera text-white"></i>
              </div>
            </div>
            <small class="text-muted fw-medium mt-2">Cliquez sur la photo pour la modifier</small>
          </div>

          <!-- Formulaire caché pour le changement automatique -->
          <form id="photoAutoForm" action="{{ route('profile.updatePhoto') }}" method="POST" enctype="multipart/form-data" style="display: none;">
            @csrf
            <input type="file" name="photo" id="photoAutoInput" accept="image/*">
          </form>

          <!-- Données utilisateur -->
          <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control border-2 bg-light" id="name" value="{{ $user->name }}" readonly>
                  <label for="name"><i class="bi bi-person me-1"></i>Nom</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control border-2 bg-light" id="prenom" value="{{ $user->prenom }}" readonly>
                  <label for="prenom"><i class="bi bi-person-check me-1"></i>Prénom</label>
                </div>
              </div>
              <div class="col-12">
                <div class="form-floating">
                  <input type="email" class="form-control border-2 bg-light" id="email" value="{{ $user->email }}" readonly>
                  <label for="email"><i class="bi bi-envelope me-1"></i>Email</label>
                </div>
              </div>
              <div class="col-12">
                <div class="form-floating">
                  <input type="date" name="date_naissance" id="date_naissance"
                    value="{{ old('date_naissance', $user->date_naissance ?? '2000-01-01') }}"
                    class="form-control border-2 @error('date_naissance') is-invalid @enderror">
                  <label for="date_naissance"><i class="bi bi-calendar me-1"></i>Date de naissance</label>
                  @error('date_naissance')
                  <div class="invalid-feedback">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="bi bi-check-circle me-1"></i> Enregistrer les modifications
              </button>
            </div>
          </form>

          @if($user->etudiant)
          <hr class="my-5">
          <h5 class="text-primary fw-bold mb-4"><i class="bi bi-mortarboard me-2"></i>Informations académiques</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <div class="form-floating">
                <input type="text" class="form-control border-2 bg-light" id="cne" value="{{ $user->etudiant->cne }}" readonly>
                <label for="cne"><i class="bi bi-card-text me-1"></i>CNE</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating">
                <input type="text" class="form-control border-2 bg-light" id="filiere" value="{{ $user->etudiant->groupe->filiere->nom_filiere ?? '-' }}" readonly>
                <label for="filiere"><i class="bi bi-diagram-3 me-1"></i>Filière</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-floating">
                <input type="text" class="form-control border-2 bg-light" id="groupe" value="{{ $user->etudiant->groupe->nom_groupe ?? '-' }}" readonly>
                <label for="groupe"><i class="bi bi-people me-1"></i>Groupe</label>
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Formulaire mot de passe -->
    <div class="col-lg-5 mb-4">
      <div class="card shadow-lg border-0 rounded-4 h-100">
        <div class="card-header bg-secondary text-white border-0 rounded-top-4 py-4">
          <h5 class="mb-0 fw-bold"><i class="bi bi-shield-lock me-2"></i>Changer le mot de passe</h5>
        </div>
        <div class="card-body p-4">
          <form action="{{ route('profile.updatePassword') }}" method="POST">
            @csrf
            <div class="mb-4">
              <div class="form-floating">
                <input type="password" name="current_password" id="current_password"
                  class="form-control border-2 @error('current_password') is-invalid @enderror" required>
                <label for="current_password"><i class="bi bi-lock me-1"></i>Mot de passe actuel</label>
                @error('current_password')
                <div class="invalid-feedback">
                  <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                </div>
                @enderror
              </div>
            </div>
            <div class="mb-4">
              <div class="form-floating">
                <input type="password" name="new_password" id="new_password"
                  class="form-control border-2 @error('new_password') is-invalid @enderror" required>
                <label for="new_password"><i class="bi bi-key me-1"></i>Nouveau mot de passe</label>
                @error('new_password')
                <div class="invalid-feedback">
                  <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                </div>
                @enderror
              </div>
            </div>
            <div class="mb-4">
              <div class="form-floating">
                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                  class="form-control border-2" required>
                <label for="new_password_confirmation"><i class="bi bi-check-circle me-1"></i>Confirmer le nouveau mot de passe</label>
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                <i class="bi bi-shield-check me-2"></i>Changer le mot de passe
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  /* Custom gradient */
  .bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  }

  /* Photo upload section */
  .photo-hover {
    transition: all 0.3s ease;
    border: 4px solid var(--bs-white) !important;
    /* Ensure white border */
  }

  .photo-hover:hover {
    transform: scale(1.05);
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
  }

  .photo-edit-icon {
    transition: all 0.3s ease;
    bottom: 0;
    right: 0;
    transform: translate(25%, 25%);
    /* Adjust position to be outside the circle */
  }

  .photo-hover:hover+.photo-edit-icon,
  .photo-edit-icon:hover {
    transform: scale(1.1) translate(25%, 25%);
  }

  /* Card improvements */
  .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
  }

  /* Form control improvements */
  .form-floating>.form-control,
  .form-floating>.form-select {
    height: calc(3.5rem + 2px);
    line-height: 1.25;
    border-radius: 0.5rem;
    /* Consistent with other forms */
    transition: all 0.3s ease;
  }

  .form-floating>label {
    padding: 1rem 0.75rem;
    font-weight: 500;
    color: #6c757d;
  }

  .form-floating>.form-control:focus~label,
  .form-floating>.form-control:not(:placeholder-shown)~label,
  .form-floating>.form-select~label {
    opacity: 0.85;
    transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    color: #0d6efd;
    /* Primary color on focus */
  }

  .form-control:focus,
  .form-select:focus {
    border-color: #667eea;
    /* Primary color for focus border */
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
  }

  .form-control.bg-light {
    background-color: #e9ecef !important;
    /* Slightly darker light background for readonly */
  }

  /* Button improvements */
  .btn {
    transition: all 0.3s ease;
  }

  .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  /* Alert improvements */
  .alert {
    animation: slideInDown 0.5s ease-out;
  }

  @keyframes slideInDown {
    from {
      transform: translateY(-100%);
      opacity: 0;
    }

    to {
      transform: translateY(0);
      opacity: 1;
    }
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .card-body {
      padding: 1.5rem !important;
    }

    .form-floating>.form-control,
    .form-floating>.form-select {
      height: calc(3rem + 2px);
    }

    .form-floating>label {
      padding: 0.75rem;
    }
  }
</style>
@endpush

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const clickablePhoto = document.getElementById('clickablePhoto');
    const photoInput = document.getElementById('photoAutoInput');
    const photoForm = document.getElementById('photoAutoForm');
    const photoToast = new bootstrap.Toast(document.getElementById('photoToast'));

    if (clickablePhoto && photoInput && photoForm) {
      // Clic sur la photo → ouvre le sélecteur
      clickablePhoto.addEventListener('click', function() {
        photoInput.click();
      });

      // Dès qu'une image est choisie
      photoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            clickablePhoto.src = e.target.result + '?v=' + new Date().getTime();
          };
          reader.readAsDataURL(file);

          const formData = new FormData(photoForm);
          const xhr = new XMLHttpRequest();
          xhr.open('POST', photoForm.action, true);
          xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
          xhr.onload = function() {
            if (xhr.status === 200) {
              const response = JSON.parse(xhr.responseText);
              // Met à jour la photo avec la vraie URL du serveur
              clickablePhoto.src = response.photoUrl + '?v=' + new Date().getTime();
              // Affiche le toast
              photoToast.show();
            } else {
              alert("❌ Une erreur est survenue lors de l'envoi.");
            }
          };
          xhr.send(formData);
        }
      });
    }

    // Auto-hide success/error alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
      setTimeout(() => {
        const bsAlert = bootstrap.Alert.getInstance(alert) || new bootstrap.Alert(alert);
        bsAlert.close();
      }, 5000); // Hide after 5 seconds
    });
  });
</script>
@endsection