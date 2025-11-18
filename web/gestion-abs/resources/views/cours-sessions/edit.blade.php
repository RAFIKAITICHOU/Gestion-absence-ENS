@extends('layouts.adminMenu')
@section('breadcrumb', 'Modifier la séance')
@section('title', 'Modifier la séance')

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
                                <i class="bi bi-calendar-event fs-2 text-white"></i>
                            </div>
                            <div>
                                <h1 class="mb-0 fw-bold">Modifier la séance</h1>
                                <p class="mb-0 opacity-75">Mettez à jour les informations de la séance de cours</p>
                            </div>
                        </div>
                        <div class="d-none d-md-block">
                            <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-pencil-square fs-1 text-white opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
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
        </div>
    </div>
    @endif
    @if($errors->any())
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
                <div class="d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
                    </div>
                    <div>
                        <strong>Erreur!</strong> Veuillez corriger les erreurs ci-dessous.
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Form Card -->
    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-header bg-light border-0 py-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-pencil-square text-primary"></i>
                    </div>
                    <h5 class="mb-0 fw-bold text-primary">Informations de la Séance</h5>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" data-bs-toggle="tooltip" title="Aide pour la modification de séances">
                    <i class="bi bi-question-circle me-1"></i>Aide
                </button>
            </div>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('cours-sessions.update', $seance->id) }}" id="seanceForm" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <!-- Section 1: Informations du cours -->
                <div class="section-divider mb-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-book text-primary"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-primary">Informations du cours</h6>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <!-- Nom du cours -->
                    <div class="col-12 col-md-6">
                        <label for="nom_cours" class="form-label fw-semibold">
                            <i class="bi bi-book me-1 text-primary"></i>Nom du cours
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-book"></i>
                            </span>
                            <input type="text" name="nom_cours" id="nom_cours" value="{{ old('nom_cours', $seance->cours->nom ?? '') }}"
                                class="form-control border-start-0 @error('nom_cours') is-invalid @enderror" placeholder="Ex: Mathématiques, Physique..." required>
                            @error('nom_cours')
                            <div class="invalid-feedback"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Semestre -->
                    <div class="col-12 col-md-3">
                        <label for="semestre" class="form-label fw-semibold">
                            <i class="bi bi-calendar-range me-1 text-primary"></i>Semestre
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-calendar-range"></i>
                            </span>
                            <select name="semestre" id="semestre" class="form-select border-start-0 @error('semestre') is-invalid @enderror" required>
                                <option value="">-- Choisir --</option>
                                @foreach(['S1','S2','S3','S4','S5','S6'] as $s)
                                <option value="{{ $s }}" {{ (old('semestre', $seance->cours->semestre ?? '') == $s) ? 'selected' : '' }}>
                                    Semestre {{ $s }}
                                </option>
                                @endforeach
                            </select>
                            @error('semestre')
                            <div class="invalid-feedback"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Professeur -->
                    <div class="col-12 col-md-3">
                        <label for="id_professeur" class="form-label fw-semibold">
                            <i class="bi bi-person-badge me-1 text-primary"></i>Professeur
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-person-badge"></i>
                            </span>
                            <select name="id_professeur" id="id_professeur" class="form-select border-start-0 @error('id_professeur') is-invalid @enderror" required>
                                <option value="">-- Choisir --</option>
                                @foreach($professeurs as $prof)
                                <option value="{{ $prof->id }}" {{ $seance->id_professeur == $prof->id ? 'selected' : '' }}>
                                    {{ $prof->user->name }} {{ $prof->user->prenom }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_professeur')
                            <div class="invalid-feedback"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Lieu et Groupe -->
                <div class="section-divider mb-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-geo-alt text-success"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-success">Lieu et participants</h6>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <!-- Salle -->
                    <div class="col-12 col-md-6">
                        <label for="id_salle" class="form-label fw-semibold">
                            <i class="bi bi-door-open me-1 text-success"></i>Nom de la salle
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-door-open"></i>
                            </span>
                            <select name="id_salle" id="id_salle" class="form-select border-start-0 @error('id_salle') is-invalid @enderror" required>
                                <option value="">-- Choisir une salle --</option>
                                @foreach($salles as $salle)
                                <option value="{{ $salle->id }}" {{ $seance->id_salle == $salle->id ? 'selected' : '' }}>
                                    {{ $salle->nom }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_salle')
                            <div class="invalid-feedback"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Groupe -->
                    <div class="col-12 col-md-6">
                        <label for="groupe_id" class="form-label fw-semibold">
                            <i class="bi bi-people me-1 text-success"></i>Groupe
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-people"></i>
                            </span>
                            <select name="groupe_id" id="groupe_id" class="form-select border-start-0 @error('groupe_id') is-invalid @enderror" required>
                                <option value="">-- Choisir --</option>
                                @foreach(\App\Models\Filiere::with('groupes')->get() as $filiere)
                                <optgroup label="{{ $filiere->nom_filiere }}">
                                    @foreach($filiere->groupes as $groupe)
                                    <option value="{{ $groupe->id }}" {{ old('groupe_id', $seance->groupe_id ?? '') == $groupe->id ? 'selected' : '' }}>
                                        {{ $groupe->nom_groupe }}
                                    </option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                            @error('groupe_id')
                            <div class="invalid-feedback"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Planification -->
                <div class="section-divider mb-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-clock-history text-warning"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-warning">Planification</h6>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <!-- Date -->
                    <div class="col-12 col-md-3">
                        <label for="date" class="form-label fw-semibold">
                            <i class="bi bi-calendar-event me-1 text-warning"></i>Date
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-calendar-event"></i>
                            </span>
                            <input type="date" name="date" id="date" value="{{ old('date', $seance->date) }}"
                                class="form-control border-start-0 @error('date') is-invalid @enderror" required>
                            @error('date')
                            <div class="invalid-feedback"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Heure début -->
                    <div class="col-12 col-md-3">
                        <label for="heure_debut" class="form-label fw-semibold">
                            <i class="bi bi-clock me-1 text-warning"></i>Heure début
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-clock"></i>
                            </span>
                            <input type="time" name="heure_debut" id="heure_debut" value="{{ old('heure_debut', $seance->heure_debut) }}"
                                class="form-control border-start-0 @error('heure_debut') is-invalid @enderror" required>
                            @error('heure_debut')
                            <div class="invalid-feedback"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Heure fin -->
                    <div class="col-12 col-md-3">
                        <label for="heure_fin" class="form-label fw-semibold">
                            <i class="bi bi-clock-fill me-1 text-warning"></i>Heure fin
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-clock-fill"></i>
                            </span>
                            <input type="time" name="heure_fin" id="heure_fin" value="{{ old('heure_fin', $seance->heure_fin) }}"
                                class="form-control border-start-0 @error('heure_fin') is-invalid @enderror" required>
                            @error('heure_fin')
                            <div class="invalid-feedback"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Duplication -->
                    <div class="col-12 col-md-3">
                        <label for="repeat_weeks" class="form-label fw-semibold">
                            <i class="bi bi-arrow-repeat me-1 text-warning"></i>Dupliquer (semaines)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-arrow-repeat"></i>
                            </span>
                            <input type="number" name="repeat_weeks" id="repeat_weeks" value="0" min="0" max="20"
                                class="form-control border-start-0" placeholder="0">
                        </div>
                        <div class="form-text">
                            <small class="text-muted">0 = ne pas dupliquer</small>
                        </div>
                    </div>
                </div>

                <!-- Durée calculée -->
                <div class="card bg-light border-0 shadow-sm mb-4" id="dureeCard" style="display: none;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-hourglass-split text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold text-info">Durée de la séance</h6>
                                <span id="dureeText" class="text-muted"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations supplémentaires -->
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <div class="card bg-light border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-calendar-plus text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold text-success">Date de création</h6>
                                        <small class="text-muted">{{ $seance->created_at->format('d/m/Y à H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card bg-light border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-pencil-square text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold text-warning">Dernière modification</h6>
                                        <small class="text-muted">{{ $seance->updated_at->format('d/m/Y à H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex flex-column flex-sm-row gap-2 pt-3 border-top">
                    <button type="submit" class="btn btn-primary btn-lg flex-fill shadow-sm">
                        <i class="bi bi-save me-2"></i>Mettre à jour la séance
                        <div class="spinner-border spinner-border-sm ms-2 d-none" role="status" id="loadingSpinner">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </button>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-lg flex-fill">
                        <i class="bi bi-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Help Card -->
    <div class="card border-0 shadow-lg mt-4 rounded-4">
        <div class="card-body bg-light p-4">
            <div class="d-flex align-items-start">
                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3 mt-1">
                    <i class="bi bi-lightbulb text-info"></i>
                </div>
                <div>
                    <h6 class="mb-2 fw-bold text-info">Conseils pour la modification de séance</h6>
                    <ul class="mb-2 text-muted small ps-3">
                        <li>Vérifiez la disponibilité de la salle avant de modifier</li>
                        <li>Assurez-vous que le professeur est disponible aux nouvelles heures</li>
                        <li>La duplication créera des séances identiques sur plusieurs semaines</li>
                        <li>Les modifications affecteront l'emploi du temps du groupe</li>
                    </ul>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <a href="#" class="badge bg-primary bg-opacity-10 text-primary text-decoration-none py-2 px-3 rounded-pill">
                            <i class="bi bi-calendar me-1"></i>Voir l'emploi du temps
                        </a>
                        <a href="#" class="badge bg-success bg-opacity-10 text-success text-decoration-none py-2 px-3 rounded-pill">
                            <i class="bi bi-building me-1"></i>Vérifier les salles
                        </a>
                        <a href="#" class="badge bg-warning bg-opacity-10 text-warning text-decoration-none py-2 px-3 rounded-pill">
                            <i class="bi bi-person-badge me-1"></i>Planning professeurs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        const form = document.getElementById('seanceForm');
        const submitBtn = form.querySelector('button[type="submit"]');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const heureDebut = document.getElementById('heure_debut');
        const heureFin = document.getElementById('heure_fin');
        const dureeCard = document.getElementById('dureeCard');
        const dureeText = document.getElementById('dureeText');

        // Form submission with loading state
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                // Focus on first invalid field
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            } else {
                submitBtn.disabled = true;
                loadingSpinner.classList.remove('d-none');
                submitBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Mise à jour en cours...';
            }
            form.classList.add('was-validated');
        });

        // Calculate and display duration
        function calculateDuration() {
            if (heureDebut.value && heureFin.value) {
                const debut = new Date(`2000-01-01T${heureDebut.value}`);
                const fin = new Date(`2000-01-01T${heureFin.value}`);
                if (fin > debut) {
                    const diffMs = fin - debut;
                    const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
                    const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
                    let durationText = '';
                    if (diffHours > 0) {
                        durationText += `${diffHours}h`;
                    }
                    if (diffMinutes > 0) {
                        durationText += `${diffMinutes > 0 && diffHours > 0 ? ' ' : ''}${diffMinutes}min`;
                    }
                    dureeText.textContent = durationText;
                    dureeCard.style.display = 'block';
                } else {
                    dureeCard.style.display = 'none';
                }
            } else {
                dureeCard.style.display = 'none';
            }
        }

        // Time validation
        function validateTimes() {
            if (heureDebut.value && heureFin.value) {
                const debut = new Date(`2000-01-01T${heureDebut.value}`);
                const fin = new Date(`2000-01-01T${heureFin.value}`);
                if (fin <= debut) {
                    heureFin.setCustomValidity('L\'heure de fin doit être postérieure à l\'heure de début');
                    heureFin.classList.add('is-invalid');
                } else {
                    heureFin.setCustomValidity('');
                    heureFin.classList.remove('is-invalid');
                    heureFin.classList.add('is-valid');
                }
            }
        }

        // Event listeners for time changes
        heureDebut.addEventListener('change', function() {
            calculateDuration();
            validateTimes();
            if (this.value) {
                this.classList.add('is-valid');
            }
        });
        heureFin.addEventListener('change', function() {
            calculateDuration();
            validateTimes();
        });

        // Initial calculation
        calculateDuration();

        // Form validation feedback for all inputs
        const inputs = form.querySelectorAll('input, select');
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

        // Smooth animations for cards
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endsection

@push('styles')
<style>
    /* Custom gradient */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    .bg-gradient-danger {
        background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%) !important;
    }

    /* Section dividers */
    .section-divider {
        position: relative;
        padding-left: 1rem;
        border-left: 4px solid transparent;
    }

    .section-divider:nth-of-type(1) {
        border-left-color: #667eea;
        /* Primary color for course info */
    }

    .section-divider:nth-of-type(2) {
        border-left-color: #198754;
        /* Success color for resources */
    }

    .section-divider:nth-of-type(3) {
        border-left-color: #ffc107;
        /* Warning color for time planning */
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

    /* Alert enhancements */
    .alert {
        border-radius: 1rem;
        border: none;
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

    /* Loading state for submit button */
    .btn:disabled {
        position: relative;
        color: transparent !important;
    }

    .btn:disabled::after {
        content: "";
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid transparent;
        border-top-color: currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
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

    .card:nth-child(4) {
        animation-delay: 0.4s;
    }

    @keyframes cardEntrance {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
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
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }

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