@extends('layouts.adminMenu')

@section('title', 'Modifier un Groupe')
@section('breadcrumb', 'Modifier un Groupe')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-body bg-gradient-primary text-white py-4 px-4 rounded">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                                <i class="bi bi-people-gear fs-2 text-white"></i>
                            </div>
                            <div>
                                <h1 class="mb-0 fw-bold">Modifier le Groupe</h1>
                                <p class="mb-0 opacity-75">Mettez à jour les informations du groupe</p>
                            </div>
                        </div>
                        <div class="d-none d-md-block">
                            <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-collection fs-1 text-white opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-people text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-primary">Informations du Groupe</h5>
                            <small class="text-muted">Modifiez les données du groupe</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('groupes.update', $groupe->id) }}" method="POST" id="groupeForm">
                        @csrf
                        @method('PUT')

                        <!-- Nom du groupe -->
                        <div class="section-divider mb-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-type text-success"></i>
                                </div>
                                <h6 class="mb-0 fw-bold text-success">Nom du Groupe</h6>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="nom_groupe" class="form-label fw-semibold text-dark">
                                <i class="bi bi-people-fill text-success me-1"></i>Nom
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-people"></i>
                                </span>
                                <input type="text" name="nom_groupe" id="nom_groupe"
                                    value="{{ old('nom_groupe', $groupe->nom_groupe) }}"
                                    class="form-control border-start-0 @error('nom_groupe') is-invalid @enderror"
                                    placeholder="Nom du groupe" required>
                            </div>
                            @error('nom_groupe')
                            <div class="invalid-feedback d-block">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle text-info me-1"></i>Le nom doit être unique et identifiable
                            </div>
                        </div>

                        <!-- Filière -->
                        <div class="section-divider mb-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-journal-code text-primary"></i>
                                </div>
                                <h6 class="mb-0 fw-bold text-primary">Filière Associée</h6>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="id_filiere" class="form-label fw-semibold text-dark">
                                <i class="bi bi-diagram-3 text-primary me-1"></i>Filière
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-diagram-3"></i>
                                </span>
                                <select name="id_filiere" id="id_filiere"
                                    class="form-select border-start-0 @error('id_filiere') is-invalid @enderror" required>
                                    <option value="">-- Sélectionner une filière --</option>
                                    @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}"
                                        {{ $filiere->id == $groupe->id_filiere ? 'selected' : '' }}>
                                        {{ $filiere->nom_filiere }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('id_filiere')
                            <div class="invalid-feedback d-block">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-lightbulb text-warning me-1"></i>Le groupe sera lié à cette filière
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body p-3 d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="bi bi-calendar-plus text-success"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Créé le</h6>
                                            <small class="text-muted">{{ $groupe->created_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body p-3 d-flex align-items-center">
                                        <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="bi bi-journal-check text-info"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Filière actuelle</h6>
                                            <small class="text-muted">
                                                {{ $filieres->firstWhere('id', $groupe->id_filiere)?->nom_filiere }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="d-flex flex-column flex-sm-row gap-2 pt-4 border-top mt-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                                <i class="bi bi-check-circle me-2"></i>Mettre à jour le groupe
                                <div class="spinner-border spinner-border-sm ms-2 d-none" role="status" id="loadingSpinner">
                                    <span class="visually-hidden">Chargement...</span>
                                </div>
                            </button>
                            <a href="{{ url('/admin/configuration') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                                <i class="bi bi-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body bg-light">
                    <div class="d-flex align-items-start">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3 mt-1">
                            <i class="bi bi-question-circle text-warning"></i>
                        </div>
                        <div>
                            <h6 class="mb-2">Conseils</h6>
                            <ul class="mb-2 text-muted small">
                                <li>Le nom du groupe doit être unique</li>
                                <li>La filière sélectionnée impacte l'organisation pédagogique</li>
                            </ul>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-person-plus me-1"></i>Étudiants
                                </span>
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-book-half me-1"></i>Cours
                                </span>
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    <i class="bi bi-calendar-event me-1"></i>Planning
                                </span>
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
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    .section-divider {
        padding-left: 1rem;
        border-left: 4px solid #0d6efd;
    }

    .card {
        opacity: 0;
        transform: translateY(20px);
        animation: cardEntrance 0.6s ease forwards;
    }

    @keyframes cardEntrance {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

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

    .badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('groupeForm');
        const submitBtn = form.querySelector('button[type="submit"]');
        const spinner = document.getElementById('loadingSpinner');

        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Mise à jour en cours...';
        });

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
    });
</script>
@endpush