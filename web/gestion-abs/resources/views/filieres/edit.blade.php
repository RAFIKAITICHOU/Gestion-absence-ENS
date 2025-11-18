@extends('layouts.adminMenu')

@section('title', 'Modifier une Filière')
@section('breadcrumb', 'Modifier une Filière')

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
                                <i class="bi bi-journal-text fs-2 text-white"></i>
                            </div>
                            <div>
                                <h1 class="mb-0 fw-bold">Modifier la Filière</h1>
                                <p class="mb-0 opacity-75">Mettez à jour les informations de la filière</p>
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
                            <i class="bi bi-pencil-square text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-primary">Informations de la Filière</h5>
                            <small class="text-muted">Modifiez les données associées à cette filière</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('filieres.update', $filiere->id) }}" method="POST" id="filiereForm">
                        @csrf
                        @method('PUT')

                        <!-- Section Divider -->
                        <div class="section-divider mb-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-tag text-primary"></i>
                                </div>
                                <h6 class="mb-0 fw-bold text-primary">Nom de la Filière</h6>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="nom_filiere" class="form-label fw-semibold text-dark">
                                <i class="bi bi-tag-fill text-primary me-1"></i>Nom
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-bookmark-star"></i>
                                </span>
                                <input type="text" name="nom_filiere" id="nom_filiere"
                                    value="{{ old('nom_filiere', $filiere->nom_filiere) }}"
                                    class="form-control border-start-0 @error('nom_filiere') is-invalid @enderror"
                                    placeholder="Ex: Informatique" required>
                            </div>
                            @error('nom_filiere')
                            <div class="invalid-feedback d-block">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle text-info me-1"></i>Le nom doit être unique et descriptif.
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="section-divider mb-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-align-start text-primary"></i>
                                </div>
                                <h6 class="mb-0 fw-bold text-primary">Description</h6>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold text-dark">
                                <i class="bi bi-file-earmark-text text-primary me-1"></i>Description
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 align-items-start pt-3">
                                    <i class="bi bi-file-earmark-text"></i>
                                </span>
                                <textarea name="description" id="description"
                                    class="form-control border-start-0 @error('description') is-invalid @enderror"
                                    rows="4" placeholder="Décrivez la filière, ses objectifs...">{{ old('description', $filiere->description) }}</textarea>
                            </div>
                            @error('description')
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
                                            <small class="text-muted">{{ $filiere->created_at->format('d/m/Y') }}</small>
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
                                            <small class="text-muted">{{ $filiere->updated_at->format('d/m/Y à H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex flex-column flex-sm-row gap-2 pt-4 border-top mt-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                                <i class="bi bi-check-circle me-2"></i>Mettre à jour la filière
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

            <!-- Help Card -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body bg-light">
                    <div class="d-flex align-items-start">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3 mt-1">
                            <i class="bi bi-question-circle text-warning"></i>
                        </div>
                        <div>
                            <h6 class="mb-2">Conseils</h6>
                            <ul class="mb-2 text-muted small">
                                <li>Le nom de la filière doit être clair et unique</li>
                                <li>La description permet aux étudiants de comprendre les objectifs</li>
                            </ul>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-check-circle me-1"></i>Nom unique
                                </span>
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    <i class="bi bi-lightbulb me-1"></i>Description claire
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

    .form-control:focus {
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
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filiereForm');
        const submitBtn = form.querySelector('button[type="submit"]');
        const spinner = document.getElementById('loadingSpinner');

        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Mise à jour en cours...';
        });

        const inputs = form.querySelectorAll('input, textarea');
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