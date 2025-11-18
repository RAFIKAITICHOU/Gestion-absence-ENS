@extends('layouts.adminMenu')

@section('title', 'Modifier une Salle')
@section('breadcrumb', 'Modifier une Salle')

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
                                <i class="bi bi-door-open fs-2 text-white"></i>
                            </div>
                            <div>
                                <h1 class="mb-0 fw-bold">Modifier la Salle</h1>
                                <p class="mb-0 opacity-75">Mettez à jour les informations de la salle</p>
                            </div>
                        </div>
                        <div class="d-none d-md-block">
                            <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-building fs-1 text-white opacity-50"></i>
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
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                    </div>
                    <div>
                        <strong>Succès !</strong> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
                    </div>
                    <div>
                        <strong>Erreur !</strong> {{ session('error') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-door-closed text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-primary">Informations de la Salle</h5>
                            <small class="text-muted">Modifiez les données de la salle</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('salles.update', $salle->id) }}" method="POST" id="salleForm">
                        @csrf
                        @method('PUT')

                        <!-- Nom de la salle -->
                        <div class="section-divider mb-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-type text-success"></i>
                                </div>
                                <h6 class="mb-0 fw-bold text-success">Nom de la Salle</h6>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="nom" class="form-label fw-semibold text-dark">
                                <i class="bi bi-building text-primary me-1"></i>Nom
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-door-open"></i>
                                </span>
                                <input type="text" name="nom" id="nom"
                                    value="{{ old('nom', $salle->nom) }}"
                                    class="form-control border-start-0 @error('nom') is-invalid @enderror"
                                    placeholder="Ex: Salle A101, Amphi 1" required>
                            </div>
                            @error('nom')
                            <div class="invalid-feedback d-block">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle text-info me-1"></i>Nom clair et facilement identifiable
                            </div>
                        </div>

                        <!-- Équipements -->
                        <div class="section-divider mb-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-tools text-warning"></i>
                                </div>
                                <h6 class="mb-0 fw-bold text-warning">Équipements</h6>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="equipements" class="form-label fw-semibold text-dark">
                                <i class="bi bi-list-check text-primary me-1"></i>Équipements disponibles
                            </label>
                            <textarea name="equipements" id="equipements"
                                class="form-control @error('equipements') is-invalid @enderror"
                                rows="4"
                                placeholder="Ex: Tableau blanc, Ordinateur, Climatisation...">{{ old('equipements', $salle->equipements) }}</textarea>
                            @error('equipements')
                            <div class="invalid-feedback d-block">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Projecteur -->
                        <div class="section-divider mb-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-projector text-info"></i>
                                </div>
                                <h6 class="mb-0 fw-bold text-info">Projecteur</h6>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="projecteurs" class="form-label fw-semibold text-dark">
                                <i class="bi bi-camera-video text-primary me-1"></i>Présence de projecteur
                            </label>
                            <select name="projecteurs" id="projecteurs"
                                class="form-select @error('projecteurs') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Sélectionnez --</option>
                                <option value="1" {{ $salle->projecteurs ? 'selected' : '' }}>Oui</option>
                                <option value="0" {{ !$salle->projecteurs ? 'selected' : '' }}>Non</option>
                            </select>
                            @error('projecteurs')
                            <div class="invalid-feedback d-block">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                            @enderror
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
                                            <h6 class="mb-1">Créée le</h6>
                                            <small class="text-muted">{{ $salle->created_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body p-3 d-flex align-items-center">
                                        <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="bi bi-clock-history text-info"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Dernière modification</h6>
                                            <small class="text-muted">{{ $salle->updated_at->format('d/m/Y à H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex flex-column flex-sm-row gap-2 pt-4 border-top mt-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                                <i class="bi bi-check-circle me-2"></i>Mettre à jour la salle
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

    .alert {
        animation: slideInDown 0.5s ease-out;
    }

    @keyframes slideInDown {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('salleForm');
        const submitBtn = form.querySelector('button[type="submit"]');
        const spinner = document.getElementById('loadingSpinner');

        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Mise à jour...';
        });

        const inputs = form.querySelectorAll('input, textarea, select');
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