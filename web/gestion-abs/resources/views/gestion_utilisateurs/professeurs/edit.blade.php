@extends('layouts.'.$menu)
@section('breadcrumb', 'Modifier le professeur')

@section('content')
<div class="container-fluid py-4">
  <!-- Header Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-lg">
        <div class="card-body bg-gradient-primary text-white py-4 px-4 rounded">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                <i class="bi bi-person-gear fs-2 text-white"></i>
              </div>
              <div>
                <h1 class="mb-0 fw-bold">Modifier le professeur</h1>
                <p class="mb-0 opacity-75">Mettez à jour les informations du professeur</p>
              </div>
            </div>
            <div class="d-none d-md-block">
              <div class="bg-white bg-opacity-10 rounded-3 p-3">
                <i class="bi bi-person-badge fs-1 text-white opacity-50"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-12 col-xl-10">
      <!-- Success Alert -->
      @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center">
          <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
            <i class="bi bi-check-circle-fill fs-4 text-success"></i>
          </div>
          <div>
            <strong>Succès!</strong> {{ session('success') }}
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      <!-- Error Alert -->
      @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-start">
          <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3 mt-1">
            <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
          </div>
          <div class="flex-grow-1">
            <strong>Erreurs détectées!</strong>
            <ul class="mb-0 mt-2">
              @foreach($errors->all() as $error)
              <li class="mb-1">
                <i class="bi bi-arrow-right me-1"></i>{{ $error }}
              </li>
              @endforeach
            </ul>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      <div class="row g-4">
        <!-- Main Form -->
        <div class="col-12 col-lg-8">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0 py-3">
              <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                  <i class="bi bi-person-fill-gear text-primary"></i>
                </div>
                <div>
                  <h5 class="mb-0 fw-bold text-primary">Informations du professeur</h5>
                  <small class="text-muted">Modifiez les données personnelles et de contact</small>
                </div>
              </div>
            </div>

            <div class="card-body p-4">
              <form method="POST" action="{{ route('professeurs.update', $professeur->id) }}" id="professeurForm">
                @csrf
                @method('PUT')

                <!-- Section Identité -->
                <div class="section-divider mb-4">
                  <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                      <i class="bi bi-person text-success"></i>
                    </div>
                    <h6 class="mb-0 fw-bold text-success">Identité</h6>
                  </div>
                </div>

                <div class="row g-3 mb-4">
                  <!-- Nom -->
                  <div class="col-12 col-md-6">
                    <label for="name" class="form-label fw-semibold">
                      <i class="bi bi-person me-1 text-success"></i>Nom
                      <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-person"></i>
                      </span>
                      <input type="text" name="name" id="name"
                        value="{{ old('name', $professeur->user->name) }}"
                        class="form-control border-start-0 @error('name') is-invalid @enderror"
                        placeholder="Nom de famille" required>
                    </div>
                    @error('name')
                    <div class="invalid-feedback d-block">
                      <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                    @enderror
                  </div>

                  <!-- Prénom -->
                  <div class="col-12 col-md-6">
                    <label for="prenom" class="form-label fw-semibold">
                      <i class="bi bi-person-badge me-1 text-success"></i>Prénom
                      <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-person-badge"></i>
                      </span>
                      <input type="text" name="prenom" id="prenom"
                        value="{{ old('prenom', $professeur->user->prenom) }}"
                        class="form-control border-start-0 @error('prenom') is-invalid @enderror"
                        placeholder="Prénom" required>
                    </div>
                    @error('prenom')
                    <div class="invalid-feedback d-block">
                      <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                    @enderror
                  </div>
                </div>

                <!-- Section Contact -->
                <div class="section-divider mb-4">
                  <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                      <i class="bi bi-envelope text-warning"></i>
                    </div>
                    <h6 class="mb-0 fw-bold text-warning">Contact</h6>
                  </div>
                </div>

                <div class="row g-3 mb-4">
                  <!-- Email -->
                  <div class="col-12">
                    <label for="email" class="form-label fw-semibold">
                      <i class="bi bi-envelope me-1 text-warning"></i>Adresse email
                      <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-envelope"></i>
                      </span>
                      <input type="email" name="email" id="email"
                        value="{{ old('email', $professeur->user->email) }}"
                        class="form-control border-start-0 @error('email') is-invalid @enderror"
                        placeholder="exemple@etablissement.edu" required>
                    </div>
                    @error('email')
                    <div class="invalid-feedback d-block">
                      <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                    @enderror
                    <div class="form-text">
                      <i class="bi bi-info-circle text-info me-1"></i>Utilisée pour la connexion et les notifications
                    </div>
                  </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex flex-column flex-sm-row gap-2 pt-3 border-top">
                  <button type="submit" class="btn btn-success btn-lg rounded-pill px-4">
                    <i class="bi bi-check-circle me-2"></i>Mettre à jour le professeur
                    <div class="spinner-border spinner-border-sm ms-2 d-none" role="status" id="loadingSpinner">
                      <span class="visually-hidden">Chargement...</span>
                    </div>
                  </button>
                  <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                  </a>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="col-12 col-lg-4">
          <div class="row g-4">
            <!-- Preview Card -->
            <div class="col-12">
              <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                  <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                      <i class="bi bi-eye text-info"></i>
                    </div>
                    <h6 class="mb-0 fw-bold text-info">Aperçu du profil</h6>
                  </div>
                </div>

                <div class="card-body p-4">
                  <div class="d-flex align-items-center mb-3">
                    <div class="avatar-preview me-3">
                      <div class="avatar-circle">
                        <span id="avatar-initials">
                          {{ substr($professeur->user->name, 0, 1) }}{{ substr($professeur->user->prenom, 0, 1) }}
                        </span>
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1" id="preview-name">{{ $professeur->user->name }} {{ $professeur->user->prenom }}</h6>
                      <p class="mb-1 text-muted" id="preview-email">{{ $professeur->user->email }}</p>
                      <span class="badge bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-person-badge me-1"></i>Professeur
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Info Cards -->
            <div class="col-12">
              <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                  <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                      <i class="bi bi-calendar-plus text-success"></i>
                    </div>
                    <div>
                      <h6 class="mb-1">Date de création</h6>
                      <small class="text-muted">{{ $professeur->created_at->format('d/m/Y à H:i') }}</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12">
              <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                  <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                      <i class="bi bi-pencil text-warning"></i>
                    </div>
                    <div>
                      <h6 class="mb-1">Dernière modification</h6>
                      <small class="text-muted">{{ $professeur->updated_at->format('d/m/Y à H:i') }}</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Help Card -->
            <div class="col-12">
              <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                  <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                      <i class="bi bi-lightbulb text-info"></i>
                    </div>
                    <h6 class="mb-0 fw-bold text-info">Conseils</h6>
                  </div>
                </div>

                <div class="card-body p-4">
                  <ul class="mb-3 text-muted small">
                    <li class="mb-2">L'email doit être unique dans le système</li>
                    <li class="mb-2">Utilisez l'email institutionnel si possible</li>
                    <li class="mb-2">Les modifications affectent tous les cours assignés</li>
                    <li>Le professeur recevra une notification des changements</li>
                  </ul>

                  <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary">
                      <i class="bi bi-calendar me-1"></i>Emploi du temps
                    </span>
                    <span class="badge bg-success bg-opacity-10 text-success">
                      <i class="bi bi-book me-1"></i>Cours
                    </span>
                    <span class="badge bg-warning bg-opacity-10 text-warning">
                      <i class="bi bi-people me-1"></i>Étudiants
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
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

  /* Section dividers */
  .section-divider {
    position: relative;
    padding-left: 1rem;
    border-left: 4px solid transparent;
  }

  .section-divider:nth-of-type(1) {
    border-left-color: #198754;
  }

  .section-divider:nth-of-type(2) {
    border-left-color: #ffc107;
  }

  /* Avatar styling */
  .avatar-preview {
    position: relative;
  }

  .avatar-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.2rem;
    text-transform: uppercase;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
  }

  .avatar-circle:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
  }

  /* Form enhancements */
  .form-control:focus,
  .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    transform: translateY(-1px);
    transition: all 0.3s ease;
  }

  .input-group-text {
    transition: all 0.3s ease;
  }

  .form-control:focus+.input-group-text,
  .form-select:focus+.input-group-text {
    border-color: #86b7fe;
    background-color: rgba(13, 110, 253, 0.1);
  }

  /* Button enhancements */
  .btn {
    transition: all 0.3s ease;
    font-weight: 500;
  }

  .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  .btn:active {
    transform: translateY(0);
  }

  /* Card enhancements */
  .card {
    transition: all 0.3s ease;
    border-radius: 1rem;
  }

  .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
  }

  /* Badge enhancements */
  .badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
    transition: all 0.3s ease;
    cursor: pointer;
  }

  .badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  /* Animation for form validation */
  .is-invalid {
    animation: shake 0.5s ease-in-out;
  }

  @keyframes shake {

    0%,
    100% {
      transform: translateX(0);
    }

    25% {
      transform: translateX(-5px);
    }

    75% {
      transform: translateX(5px);
    }
  }

  /* Alert animations */
  .alert {
    animation: slideInDown 0.5s ease-out;
    border-radius: 1rem;
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

  /* Responsive improvements */
  @media (max-width: 768px) {
    .container-fluid {
      padding-left: 1rem;
      padding-right: 1rem;
    }

    .card-body {
      padding: 1.5rem !important;
    }

    .section-divider {
      border-left: none;
      border-top: 4px solid;
      padding-left: 0;
      padding-top: 0.5rem;
      margin-bottom: 1rem;
    }

    .avatar-circle {
      width: 50px;
      height: 50px;
      font-size: 1rem;
    }
  }

  @media (max-width: 576px) {
    .card-body {
      padding: 1rem !important;
    }

    .btn-lg {
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
    }
  }

  /* Loading state */
  .btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  /* Smooth card entrance animation */
  .card {
    opacity: 0;
    transform: translateY(20px);
    animation: cardEntrance 0.6s ease forwards;
  }

  .card:nth-child(1) {
    animation-delay: 0.1s;
  }

  .card:nth-child(2) {
    animation-delay: 0.2s;
  }

  .card:nth-child(3) {
    animation-delay: 0.3s;
  }

  @keyframes cardEntrance {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('professeurForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const nameInput = document.getElementById('name');
    const prenomInput = document.getElementById('prenom');
    const emailInput = document.getElementById('email');

    // Form submission with loading state
    form.addEventListener('submit', function(e) {
      submitBtn.disabled = true;
      loadingSpinner.classList.remove('d-none');
      submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Mise à jour en cours...';
    });

    // Update preview in real time
    function updatePreview() {
      const name = nameInput.value || 'Nom';
      const prenom = prenomInput.value || 'Prénom';
      const email = emailInput.value || 'email@exemple.com';

      // Update preview text
      document.getElementById('preview-name').textContent = `${name} ${prenom}`;
      document.getElementById('preview-email').textContent = email;

      // Update avatar initials
      const initials = (name.charAt(0) + prenom.charAt(0)).toUpperCase();
      document.getElementById('avatar-initials').textContent = initials;
    }

    // Event listeners for real-time preview
    nameInput.addEventListener('input', updatePreview);
    prenomInput.addEventListener('input', updatePreview);
    emailInput.addEventListener('input', updatePreview);

    // Email validation
    emailInput.addEventListener('blur', function() {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (this.value && !emailRegex.test(this.value)) {
        this.setCustomValidity('Veuillez saisir une adresse email valide');
        this.classList.add('is-invalid');
      } else {
        this.setCustomValidity('');
        this.classList.remove('is-invalid');
        if (this.value) {
          this.classList.add('is-valid');
        }
      }
    });

    // Form validation feedback
    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => {
      input.addEventListener('blur', function() {
        if (this.hasAttribute('required') && !this.value.trim()) {
          this.classList.add('is-invalid');
        } else {
          this.classList.remove('is-invalid');
          if (this.value.trim()) {
            this.classList.add('is-valid');
          }
        }
      });

      input.addEventListener('input', function() {
        if (this.classList.contains('is-invalid') && this.value.trim()) {
          this.classList.remove('is-invalid');
          this.classList.add('is-valid');
        }
      });
    });

    // Name formatting (capitalize first letter)
    const nameInputs = [nameInput, prenomInput];
    nameInputs.forEach(input => {
      input.addEventListener('blur', function() {
        if (this.value) {
          this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();
          updatePreview(); // Update preview after formatting
        }
      });
    });

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
      setTimeout(() => {
        if (alert && alert.parentNode) {
          alert.style.transition = 'all 0.5s ease';
          alert.style.opacity = '0';
          alert.style.transform = 'translateY(-20px)';
          setTimeout(() => {
            if (alert.parentNode) {
              alert.remove();
            }
          }, 500);
        }
      }, 5000);
    });

    // Confirmation for password reset
    const resetPasswordBtn = document.querySelector('button[type="submit"][class*="warning"]');
    if (resetPasswordBtn) {
      resetPasswordBtn.addEventListener('click', function(e) {
        if (!confirm('Êtes-vous sûr de vouloir réinitialiser le mot de passe de ce professeur ?')) {
          e.preventDefault();
        }
      });
    }
  });
</script>
@endpush