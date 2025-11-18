@extends('layouts.'.$menu)

@section('title', 'Ajouter un administrateur')
@section('breadcrumb', 'Ajouter un administrateur')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg card-animated" data-delay="0.1">
                <div class="card-body bg-primary-gradient text-white py-4 px-4 rounded">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape bg-white bg-opacity-25 rounded-circle p-3 me-3">
                                <i class="fas fa-user-plus fa-lg text-white"></i> 
                            </div>
                            <div>
                                <h1 class="mb-0 fw-bold">Ajouter un administrateur</h1>
                                <p class="mb-0 opacity-75">Créez un nouveau compte administrateur ou importez en masse</p>
                            </div>
                        </div>
                        <div class="d-none d-md-block">
                            <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                <i class="fas fa-user-cog fa-2x text-white opacity-50"></i>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Message de succès --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4 card-animated" data-delay="0.2" role="alert">
        <div class="d-flex align-items-center">
            <div class="icon-shape bg-success bg-opacity-10 rounded-circle p-2 me-3">
                <i class="fas fa-check-circle text-success"></i>  
            </div>
            <div><strong>Succès!</strong> {{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
    @endif

    {{-- ❌ Affichage des erreurs --}}
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4 card-animated" data-delay="0.2" role="alert">
        <div class="d-flex align-items-start">
            <div class="icon-shape bg-danger bg-opacity-10 rounded-circle p-2 me-3 mt-1">
                <i class="fas fa-exclamation-triangle text-danger"></i>  
            </div>
            <div class="flex-grow-1">
                <strong>Veuillez corriger les erreurs suivantes :</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                    <li><i class="fas fa-arrow-right me-1"></i>{{ $error }}</li>  
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
    @endif

    <div class="row g-4">
        {{-- 🧾 Formulaire d'ajout --}}
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 h-100 card-animated" data-delay="0.3">
                <div class="card-header bg-light border-bottom-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-user-plus text-primary"></i> 
                        </div>
                        <h5 class="mb-0 text-dark fw-bold">Informations du nouvel administrateur</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admins.store') }}" id="addAdminForm">
                        @csrf
                        <!-- Section Identité -->
                        <div class="section-header primary-border mb-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-id-card text-primary"></i>
                                </div>
                                <h6 class="mb-0 fw-bold text-primary">Identité</h6>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold text-dark"><i class="fas fa-user me-2 text-primary"></i> Nom<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-signature text-muted"></i></span>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control shadow-sm @error('name') is-invalid @enderror" placeholder="Nom de famille" required>
                                </div>
                                @error('name')
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="prenom" class="form-label fw-semibold text-dark"><i class="fas fa-user-tag me-2 text-primary"></i> Prénom<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-id-badge text-muted"></i></span>
                                    <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" class="form-control shadow-sm @error('prenom') is-invalid @enderror" placeholder="Prénom" required>
                                </div>
                                @error('prenom')
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Section Accès -->
                        <div class="section-header warning-border mb-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-key text-warning"></i>
                                </div>
                                <h6 class="mb-0 fw-bold text-warning">Accès et sécurité</h6>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold text-dark"><i class="fas fa-at me-2 text-warning"></i> Email<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control shadow-sm @error('email') is-invalid @enderror" placeholder="admin@etablissement.edu" required>
                            </div>
                            @error('email')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                            <div class="form-text"><i class="fas fa-shield-alt text-warning me-1"></i> Cette adresse sera utilisée pour la connexion</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark"><i class="fas fa-lock me-2 text-info"></i> Mot de passe (automatique)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-key text-muted"></i></span>
                                <input type="text" class="form-control shadow-sm bg-light border-start-0" value="Automatiquement généré" disabled>
                            </div>
                            <div class="form-text"><i class="fas fa-info-circle text-info me-1"></i> Le mot de passe sera généré automatiquement et envoyé à l'email.</div>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg rounded-pill px-4" id="submitAdminBtn">
                            <i class="fas fa-check-circle me-1"></i> Ajouter l'administrateur
                            <div class="spinner-border spinner-border-sm ms-2 d-none" role="status" id="loadingSpinner">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- 📥 Import / Export --}}
        <div class="col-12 col-lg-4">
            <div class="row g-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 h-100 card-animated" data-delay="0.4">
                        <div class="card-header bg-light border-bottom-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-upload text-info"></i>  
                                </div>
                                <h5 class="mb-0 text-dark fw-bold">Importer / Exporter des administrateurs</h5>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h6 class="mb-3"><i class="fas fa-file-csv me-2 text-info"></i> Importer via CSV</h6>
                            <a href="{{ route('admins.example') }}" class="btn btn-outline-primary btn-sm mb-3"><i class="fas fa-download me-1"></i> Télécharger exemple CSV Admins</a>  
                            <form method="POST" action="{{ route('admins.import') }}" enctype="multipart/form-data" class="row g-3 align-items-center" id="importAdminForm">
                                @csrf
                                <div class="col-md-6">
                                    <input type="file" name="csv_file" id="csv_file" class="form-control @error('csv_file') is-invalid @enderror" required>
                                    @error('csv_file')
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary" id="submitImportBtn">
                                        <i class="fas fa-arrow-up-from-bracket me-1"></i> Importer
                                        <div class="spinner-border spinner-border-sm ms-2 d-none" role="status" id="loadingImportSpinner">
                                            <span class="visually-hidden">Chargement...</span>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm card-animated" data-delay="0.5">
                        <div class="card-header bg-light border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-lightbulb text-warning"></i>
                                </div>
                                <h6 class="mb-0 fw-bold text-warning">Conseils</h6>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <ul class="mb-3 text-muted small">
                                <li class="mb-2">L'email doit être unique dans le système</li>
                                <li class="mb-2">Le mot de passe sera envoyé par email</li>
                                <li class="mb-2">Vérifiez l'orthographe des noms</li>
                                <li>Utilisez l'import CSV pour plusieurs administrateurs</li>
                            </ul>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary"><i class="fas fa-users-cog me-1"></i>Gestion Admins</span>
                                <span class="badge bg-success bg-opacity-10 text-success"><i class="fas fa-shield-alt me-1"></i>Sécurité</span>
                                <span class="badge bg-info bg-opacity-10 text-info"><i class="fas fa-file-csv me-1"></i>Import/Export</span>
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
    /* Custom gradient for primary elements */
    .bg-primary-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    /* Icon shapes */
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Section headers/dividers (re-using the same style as section-header) */
    .section-header {
        position: relative;
        padding-left: 1rem;
        border-left: 4px solid;
        margin-left: 0.5rem;
        margin-bottom: 1.5rem;
        /* Added margin-bottom for consistency */
    }

    .section-header.primary-border {
        border-color: #0d6efd;
        /* Bootstrap primary blue */
    }

    .section-header.success-border {
        border-color: #198754;
        /* Bootstrap success green */
    }

    .section-header.warning-border {
        border-color: #ffc107;
        /* Bootstrap warning yellow */
    }

    .section-header.info-border {
        border-color: #0dcaf0;
        /* Bootstrap info cyan */
    }

    /* Avatar styling (included for consistency, even if not directly used in this form) */
    .avatar-preview {
        position: relative;
    }

    .avatar-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        /* Consistent gradient */
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
        text-transform: uppercase;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        border: 3px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }

    .avatar-circle:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    }

    /* Form enhancements */
    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        /* Consistent purple/blue focus */
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        /* Consistent purple/blue focus shadow */
        transform: translateY(-1px);
        transition: all 0.3s ease;
    }

    .input-group-text {
        transition: all 0.3s ease;
    }

    .form-control:focus+.input-group-text,
    .input-group-text:has(+ .form-control:focus) {
        border-color: #667eea;
        background-color: rgba(102, 126, 234, 0.1);
    }

    /* Button enhancements */
    .btn {
        transition: all 0.3s ease;
        font-weight: 500;
        border-radius: 0.5rem;
        /* Consistent border-radius for buttons */
    }

    .btn-lg {
        border-radius: 2rem !important;
        /* Override for rounded-pill */
    }

    .btn:hover {
        transform: translateY(-2px);
        /* Consistent lift */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn:active {
        transform: translateY(0);
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Card enhancements */
    .card {
        transition: all 0.3s ease;
        border-radius: 1rem;
        /* Consistent border-radius */
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        /* Consistent shadow */
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

    /* Alert styling and animations */
    .alert {
        animation: slideInDown 0.5s ease-out;
        border-radius: 1rem;
        /* Consistent border-radius */
    }

    .alert-warning {
        /* Specific for warning alerts */
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%);
        border-left: 4px solid #ffc107;
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

    /* Form validation animation */
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

    /* Smooth card entrance animation */
    .card-animated {
        opacity: 0;
        transform: translateY(20px);
        animation: cardEntrance 0.6s ease forwards;
    }

    /* Delays applied via data-delay attribute in JS */
    @keyframes cardEntrance {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .card-body {
            padding: 1.5rem !important;
        }

        .section-header {
            /* Unified for both section-header and section-divider */
            margin-left: 0;
            border-left: none;
            border-top: 4px solid;
            /* Change to top border on small screens */
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
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addAdminForm = document.getElementById('addAdminForm');
        const importAdminForm = document.getElementById('importAdminForm');

        // Function to apply common form logic
        function applyFormLogic(form) {
            if (!form) return;

            const submitBtn = form.querySelector('button[type="submit"]');
            const loadingSpinner = form.querySelector('#loadingSpinner') || form.querySelector('#loadingImportSpinner'); // Handle both spinners
            const nameInput = form.querySelector('#name');
            const prenomInput = form.querySelector('#prenom');
            const emailInput = form.querySelector('#email');

            // Form submission with loading state
            form.addEventListener('submit', function(e) {
                if (submitBtn) submitBtn.disabled = true;
                if (loadingSpinner) loadingSpinner.classList.remove('d-none');
                if (submitBtn) {
                    if (form.id === 'addAdminForm') {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Ajout en cours...';
                    } else if (form.id === 'importAdminForm') {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Importation en cours...';
                    }
                }
            });

            // Email validation (simplified for add form)
            if (emailInput) {
                emailInput.addEventListener('blur', function() {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    const adminEmailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(edu|org|gov|com)$/;
                    if (this.value) {
                        if (!emailRegex.test(this.value)) {
                            this.setCustomValidity('Veuillez saisir une adresse email valide');
                            this.classList.add('is-invalid');
                        } else if (!adminEmailRegex.test(this.value)) {
                            this.setCustomValidity('Utilisez une adresse email professionnelle (.edu, .org, .gov, .com)');
                            this.classList.add('is-invalid');
                        } else {
                            this.setCustomValidity('');
                            this.classList.remove('is-invalid');
                            this.classList.add('is-valid');
                        }
                    }
                });
            }

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

            // File input enhancement
            const fileInput = form.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    const fileName = this.files[0]?.name;
                    if (fileName) {
                        const label = document.createElement('small');
                        label.className = 'text-success d-block mt-1';
                        label.innerHTML = `<i class="fas fa-check-circle me-1"></i>Fichier sélectionné: ${fileName}`;
                        // Remove existing label
                        const existingLabel = this.parentNode.querySelector('.text-success');
                        if (existingLabel) {
                            existingLabel.remove();
                        }
                        this.parentNode.appendChild(label);
                    }
                });
            }
        }

        // Apply logic to the add admin form
        applyFormLogic(addAdminForm);
        applyFormLogic(importAdminForm); // Apply to import form as well

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

        // Smooth card entrance animation with delays
        const cards = document.querySelectorAll('.card-animated');
        cards.forEach((card) => {
            const delay = parseFloat(card.dataset.delay || '0');
            card.style.animationDelay = `${delay}s`;
        });
    });
</script>
@endpush