@extends('layouts.'.$menu)
@section('title', 'Ajouter un étudiant')
@section('breadcrumb', 'Ajouter un étudiant')

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
                                <i class="bi bi-person-plus-fill fs-2 text-white"></i>
                            </div>
                            <div>
                                <h1 class="mb-0 fw-bold">Ajouter un étudiant</h1>
                                <p class="mb-0 opacity-75">Créez un nouveau compte étudiant ou importez en masse</p>
                            </div>
                        </div>
                        <div class="d-none d-md-block">
                            <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-mortarboard fs-1 text-white opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                    </div>
                    <div>
                        <strong>Succès!</strong> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Error Alert -->
    @if($errors->any())
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                <div class="d-flex align-items-start">
                    <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3 mt-1">
                        <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong>Veuillez corriger les erreurs suivantes :</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                            <li class="mb-1">
                                <i class="bi bi-arrow-right me-1"></i>{{ $error }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4">
        <!-- Manual Form Section -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-person-fill-add text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-primary">Ajout manuel</h5>
                            <small class="text-muted">Saisissez les informations de l'étudiant</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('etudiants.store') }}" id="studentForm">
                        @csrf
                        <div class="row g-3">
                            <!-- Nom -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person me-1 text-primary"></i>Nom
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" name="name" value="{{ old('name') }}"
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
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person me-1 text-primary"></i>Prénom
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-person-badge"></i>
                                    </span>
                                    <input type="text" name="prenom" value="{{ old('prenom') }}"
                                        class="form-control border-start-0 @error('prenom') is-invalid @enderror"
                                        placeholder="Prénom" required>
                                </div>
                                @error('prenom')
                                <div class="invalid-feedback d-block">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- CNE -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-credit-card me-1 text-primary"></i>CNE
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-credit-card"></i>
                                    </span>
                                    <input type="text" name="cne" value="{{ old('cne') }}"
                                        class="form-control border-start-0 @error('cne') is-invalid @enderror"
                                        placeholder="Code National Étudiant" required>
                                </div>
                                @error('cne')
                                <div class="invalid-feedback d-block">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-envelope me-1 text-primary"></i>Email
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control border-start-0 @error('email') is-invalid @enderror"
                                        placeholder="adresse@email.com" required>
                                </div>
                                @error('email')
                                <div class="invalid-feedback d-block">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Groupe -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-collection me-1 text-primary"></i>Groupe
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-collection"></i>
                                    </span>
                                    <select name="groupe_id" class="form-select border-start-0 @error('groupe_id') is-invalid @enderror" required>
                                        <option value="">-- Sélectionner un groupe --</option>
                                        @foreach(\App\Models\Groupe::all() as $groupe)
                                        <option value="{{ $groupe->id }}" {{ old('groupe_id') == $groupe->id ? 'selected' : '' }}>
                                            {{ $groupe->nom_groupe }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('groupe_id')
                                <div class="invalid-feedback d-block">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Password Info -->
                            <div class="col-12">
                                <div class="alert alert-info border-0 bg-info bg-opacity-10">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-info-circle fs-4 text-info me-3"></i>
                                        <div>
                                            <strong>Mot de passe automatique :</strong>
                                            <p class="mb-0">Le mot de passe sera généré automatiquement selon le format : <code>CNE@2025</code></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-success btn-lg rounded-pill px-4">
                                <i class="bi bi-check-circle me-2"></i>Ajouter l'étudiant
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Import/Export Section -->
        <div class="col-12 col-lg-4">
            <div class="row g-4">
                <!-- CSV Import Card -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-upload text-success"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold text-success">Import CSV</h5>
                                    <small class="text-muted">Importez plusieurs étudiants</small>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Download Example -->
                            <div class="mb-4">
                                <a href="{{ route('etudiants.example') }}" class="btn btn-outline-primary w-100 rounded-pill">
                                    <i class="bi bi-download me-2"></i>Télécharger exemple CSV
                                </a>
                                <small class="text-muted d-block mt-2 text-center">
                                    <i class="bi bi-info-circle me-1"></i>Format requis pour l'import
                                </small>
                            </div>

                            <!-- Upload Form -->
                            <form method="POST" action="{{ route('etudiants.import') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-file-earmark-spreadsheet me-1 text-success"></i>Fichier CSV
                                    </label>
                                    <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                                    <div class="form-text">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Formats acceptés : .csv uniquement
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success w-100 rounded-pill">
                                    <i class="bi bi-arrow-up-circle me-2"></i>Importer les étudiants
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Export Card -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-download text-info"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold text-info">Export</h5>
                                    <small class="text-muted">Téléchargez la liste</small>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <a href="{{ route('etudiants.export') }}" class="btn btn-outline-info w-100 rounded-pill">
                                <i class="bi bi-download me-2"></i>Exporter les étudiants
                            </a>
                            <small class="text-muted d-block mt-2 text-center">
                                <i class="bi bi-file-earmark-spreadsheet me-1"></i>Format CSV
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Card -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm bg-gradient-light">
                        <div class="card-body p-4 text-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-flex mb-3">
                                <i class="bi bi-people-fill fs-2 text-primary"></i>
                            </div>
                            <h3 class="fw-bold text-primary mb-1">{{ \App\Models\Etudiant::count() }}</h3>
                            <p class="text-muted mb-0">Étudiants inscrits</p>
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

    .bg-gradient-light {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
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
    .form-control:focus~.input-group-text {
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

    /* Alert enhancements */
    .alert {
        border-radius: 1rem;
        border: none;
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

    /* Loading state */
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* File input styling */
    input[type="file"] {
        transition: all 0.3s ease;
    }

    input[type="file"]:focus {
        outline: none;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .card-body {
            padding: 1rem !important;
        }
    }

    /* Success animation */
    .alert-success {
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

    /* Form focus effects */
    .form-label {
        transition: all 0.3s ease;
    }

    .form-control:focus~.form-label,
    .form-select:focus~.form-label {
        color: #0d6efd;
        transform: translateY(-2px);
    }

    /* Custom scrollbar for alerts with long content */
    .alert ul {
        max-height: 150px;
        overflow-y: auto;
    }

    .alert ul::-webkit-scrollbar {
        width: 4px;
    }

    .alert ul::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 2px;
    }

    .alert ul::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 2px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide success alerts
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            setTimeout(() => {
                successAlert.classList.remove('show');
            }, 5000);
        }

        // Form validation enhancement
        const form = document.getElementById('studentForm');
        const inputs = form.querySelectorAll('input, select');

        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid') && this.value.trim()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });
        });

        // File input enhancement
        const fileInput = document.querySelector('input[type="file"]');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const fileName = this.files[0]?.name;
                if (fileName) {
                    const label = document.createElement('small');
                    label.className = 'text-success d-block mt-1';
                    label.innerHTML = `<i class="bi bi-check-circle me-1"></i>Fichier sélectionné: ${fileName}`;

                    // Remove existing label
                    const existingLabel = this.parentNode.querySelector('.text-success');
                    if (existingLabel) {
                        existingLabel.remove();
                    }

                    this.parentNode.appendChild(label);
                }
            });
        }

        // Submit button loading state
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Traitement...';

            // Re-enable after 3 seconds (in case of validation errors)
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }, 3000);
        });
    });
</script>
@endpush