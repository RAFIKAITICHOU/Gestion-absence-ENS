@extends('layouts.adminMenu')
@section('breadcrumb', 'Nouvelle Annonce')
@section('title', 'Nouvelle Annonce')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-body bg-gradient-primary text-white py-4 px-4 rounded">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                            <i class="bi bi-megaphone fs-2 text-white"></i>
                        </div>
                        <div>
                            <h1 class="mb-0 fw-bold">Créer une Nouvelle Annonce</h1>
                            <p class="mb-0 opacity-75">Rédigez et publiez des messages importants pour les utilisateurs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
                <div class="d-flex align-items-start">
                    <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 fw-bold text-danger">Erreurs de validation :</h6>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Announcement Form Card -->
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
            <div class="d-flex align-items-center">
                <div class="icon-shape bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                    <i class="bi bi-pencil-square text-primary"></i>
                </div>
                <h5 class="mb-0 text-dark fw-bold">Détails de l'Annonce</h5>
            </div>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('annonces.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf

                <!-- Section 1: Informations de base -->
                <div class="mb-4">
                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2"><i class="bi bi-info-circle me-2"></i>Informations de base</h6>
                    <div class="row g-3">
                        <!-- Titre de l’annonce -->
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" name="titre" id="titre" class="form-control border-2 @error('titre') is-invalid @enderror" placeholder="Titre de l’annonce" required value="{{ old('titre') }}">
                                <label for="titre"><i class="bi bi-card-heading me-1"></i>Titre de l’annonce *</label>
                                @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Contenu -->
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea name="contenu" id="contenu" rows="5" class="form-control border-2 @error('contenu') is-invalid @enderror" placeholder="Contenu de l'annonce" style="min-height: 120px;" required>{{ old('contenu') }}</textarea>
                                <label for="contenu"><i class="bi bi-text-paragraph me-1"></i>Contenu *</label>
                                @error('contenu')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Options de publication -->
                <div class="mb-4">
                    <h6 class="text-success fw-bold mb-3 border-bottom pb-2"><i class="bi bi-gear me-2"></i>Options de publication</h6>
                    <div class="row g-3">
                        <!-- Type de message -->
                        <div class="col-12 col-md-4">
                            <div class="form-floating">
                                <select name="type" id="type" class="form-select border-2 @error('type') is-invalid @enderror" required>
                                    <option value="primary" {{ old('type') == 'primary' ? 'selected' : '' }}>Information (Bleu)</option>
                                    <option value="success" {{ old('type') == 'success' ? 'selected' : '' }}>Succès (Vert)</option>
                                    <option value="warning" {{ old('type') == 'warning' ? 'selected' : '' }}>Attention (Jaune)</option>
                                    <option value="danger" {{ old('type') == 'danger' ? 'selected' : '' }}>Urgent (Rouge)</option>
                                </select>
                                <label for="type"><i class="bi bi-lightbulb me-1"></i>Type de message *</label>
                                @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Destinataires -->
                        <div class="col-12 col-md-4">
                            <div class="form-floating">
                                <select name="audience" id="audience" class="form-select border-2 @error('audience') is-invalid @enderror" required>
                                    <option value="etudiant" {{ old('audience') == 'etudiant' ? 'selected' : '' }}>Étudiants</option>
                                    <option value="professeur" {{ old('audience') == 'professeur' ? 'selected' : '' }}>Professeurs</option>
                                    <option value="all" {{ old('audience') == 'all' ? 'selected' : '' }}>Tous</option>
                                </select>
                                <label for="audience"><i class="bi bi-people me-1"></i>Destinataires *</label>
                                @error('audience')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Média (optionnel) -->
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="media" class="form-label fw-bold text-muted mb-2"><i class="bi bi-image me-1"></i>Image ou Vidéo (optionnel)</label>
                                <input type="file" name="media" id="media" class="form-control border-2 @error('media') is-invalid @enderror" accept="image/*,video/mp4,application/pdf">
                                <small class="form-text text-muted">Formats supportés : JPG, PNG, GIF, MP4, PDF (max 20 Mo)</small>
                                @error('media')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="border-top pt-4 mt-4">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('annonces.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-x-circle me-1"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                            <i class="bi bi-send-fill me-2"></i>Publier l'annonce
                        </button>
                    </div>
                </div>
            </form>
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

    /* Card enhancements */
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 1rem;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
    }

    /* Form floating labels */
    .form-floating>.form-control,
    .form-floating>.form-select {
        height: calc(3.5rem + 2px);
        line-height: 1.25;
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
        color: #667eea;
        /* Primary color for focused label */
    }

    /* Input group text for icons */
    .input-group-text {
        background-color: var(--bs-light);
        border-right: none;
        color: var(--bs-muted);
    }

    /* Form control focus state */
    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
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

    /* Section headers */
    .border-bottom {
        border-color: #dee2e6 !important;
        border-width: 2px !important;
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

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }

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

        .btn {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        .card-body {
            padding: 1rem !important;
        }

        .btn {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
        }

        .d-flex.justify-content-end {
            flex-direction: column;
            gap: 0.5rem !important;
        }
    }

    /* Smooth card entrance animation */
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
</style>
@endpush