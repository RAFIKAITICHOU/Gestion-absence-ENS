@extends('layouts.adminMenu')
@section('breadcrumb', 'Gestion des Annonces')
@section('title', 'Gestion des Annonces')

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
                                <i class="bi bi-megaphone fs-2 text-white"></i>
                            </div>
                            <div>
                                <h1 class="mb-0 fw-bold">Gestion des Annonces</h1>
                                <p class="mb-0 opacity-75">Créez, modifiez et gérez les annonces pour les utilisateurs</p>
                            </div>
                        </div>
                        <a href="{{ route('annonces.create') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                            <i class="bi bi-plus-circle me-2"></i>Nouvelle annonce
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
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
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Announcements List -->
    @forelse($annonces as $annonce)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 announcement-card">
                <div class="card-body p-4">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <h5 class="mb-0 fw-bold text-{{ $annonce->type }}">
                                    {{ $annonce->titre }}
                                </h5>
                                <span class="badge bg-secondary-subtle text-secondary ms-2 py-2 px-3 rounded-pill fw-normal">
                                    <i class="bi bi-people me-1"></i>{{ ucfirst($annonce->audience) }}
                                </span>
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>Publiée le {{ $annonce->created_at->format('d/m/Y à H:i') }}
                            </small>
                        </div>
                        <!-- Actions -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('annonces.edit', $annonce->id) }}"
                                class="btn btn-sm btn-outline-primary rounded-pill" title="Modifier">
                                <i class="bi bi-pencil"></i><span class="d-none d-md-inline ms-1">Modifier</span>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill"
                                data-bs-toggle="modal" data-bs-target="#modalSupprimer{{ $annonce->id }}"
                                title="Supprimer">
                                <i class="bi bi-trash"></i><span class="d-none d-md-inline ms-1">Supprimer</span>
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="mb-3">
                        <p class="mb-0 text-dark">{{ $annonce->contenu }}</p>
                    </div>

                    <!-- Media -->
                    @if($annonce->media)
                    <div class="mb-3 media-preview">
                        @php
                        $mediaPath = asset('storage/' . $annonce->media);
                        $extension = Str::lower(pathinfo($annonce->media, PATHINFO_EXTENSION));
                        @endphp
                        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                        <div class="text-center">
                            <img src="{{ $mediaPath }}" alt="Image annonce"
                                class="img-fluid rounded border shadow-sm" style="max-height: 350px; object-fit: contain;">
                        </div>
                        @elseif($extension === 'mp4')
                        <div class="text-center">
                            <video controls class="w-100 rounded border shadow-sm" style="max-height: 400px;">
                                <source src="{{ $mediaPath }}" type="video/mp4">
                                Votre navigateur ne supporte pas la lecture vidéo.
                            </video>
                        </div>
                        @elseif($extension === 'pdf')
                        <div class="text-center">
                            <iframe src="{{ $mediaPath }}" class="w-100 rounded border shadow-sm"
                                style="height: 500px;" frameborder="0"></iframe>
                        </div>
                        @elseif(in_array($extension, ['doc', 'docx', 'xls', 'xlsx', 'csv', 'zip']))
                        <div class="text-center">
                            <a href="{{ $mediaPath }}" class="btn btn-outline-primary rounded-pill px-4" download>
                                <i class="bi bi-download me-2"></i>Télécharger le fichier
                            </a>
                        </div>
                        @else
                        <div class="text-center text-muted p-4 bg-light rounded-3 border">
                            <i class="bi bi-file-earmark me-2 fs-4"></i>
                            <p class="mb-0">Type de fichier non supporté pour l'aperçu.</p>
                            <a href="{{ $mediaPath }}" class="btn btn-sm btn-outline-secondary mt-2 rounded-pill" download>
                                <i class="bi bi-download me-1"></i>Télécharger quand même
                            </a>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Type indicator -->
                    <div class="border-start border-{{ $annonce->type }} border-4 ps-3 pt-1 pb-1 rounded-end">
                        <small class="text-{{ $annonce->type }} fw-semibold">
                            @switch($annonce->type)
                            @case('primary')<i class="bi bi-info-circle me-1"></i>Information
                            @break
                            @case('success')<i class="bi bi-check-circle me-1"></i>Succès
                            @break
                            @case('warning')<i class="bi bi-exclamation-triangle me-1"></i>Attention
                            @break
                            @case('danger')<i class="bi bi-x-circle me-1"></i>Urgent
                            @break
                            @default<i class="bi bi-bell me-1"></i>Annonce
                            @endswitch
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="modalSupprimer{{ $annonce->id }}" tabindex="-1" aria-labelledby="modalSupprimerLabel{{ $annonce->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-gradient-danger text-white rounded-top-4">
                    <h5 class="modal-title fw-bold" id="modalSupprimerLabel{{ $annonce->id }}"><i class="bi bi-exclamation-triangle me-2"></i>Confirmer la suppression</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-trash-fill text-danger display-4 mb-3"></i>
                    <p class="lead mb-0">Voulez-vous vraiment supprimer cette annonce ?</p>
                    <p class="fw-bold text-dark mt-2">"{{ $annonce->titre }}"</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Annuler
                    </button>
                    <form action="{{ route('annonces.destroy', $annonce->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-pill px-4">
                            <i class="bi bi-trash me-1"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <!-- Empty State -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body text-center py-5">
                    <div class="text-muted">
                        <i class="bi bi-megaphone display-4 mb-3 text-primary opacity-50"></i>
                        <h5 class="fw-bold mb-2">Aucune annonce disponible</h5>
                        <p class="mb-4">Commencez par créer votre première annonce pour informer les utilisateurs.</p>
                        <a href="{{ route('annonces.create') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                            <i class="bi bi-plus-circle me-2"></i>Créer une annonce
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforelse

    <!-- Statistics -->
    @if($annonces->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-bar-chart-line me-2"></i>Statistiques des Annonces</h5>
                    <div class="row text-center g-3">
                        <div class="col-6 col-md-3">
                            <div class="p-3 bg-light rounded-3 shadow-sm h-100 d-flex flex-column justify-content-center">
                                <h4 class="mb-1 fw-bold text-primary">{{ $annonces->count() }}</h4>
                                <small class="text-muted text-uppercase">Total Annonces</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 bg-light rounded-3 shadow-sm h-100 d-flex flex-column justify-content-center">
                                <h4 class="mb-1 fw-bold text-success">{{ $annonces->where('type', 'success')->count() }}</h4>
                                <small class="text-muted text-uppercase">Succès</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 bg-light rounded-3 shadow-sm h-100 d-flex flex-column justify-content-center">
                                <h4 class="mb-1 fw-bold text-warning">{{ $annonces->where('type', 'warning')->count() }}</h4>
                                <small class="text-muted text-uppercase">Attention</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 bg-light rounded-3 shadow-sm h-100 d-flex flex-column justify-content-center">
                                <h4 class="mb-1 fw-bold text-danger">{{ $annonces->where('type', 'danger')->count() }}</h4>
                                <small class="text-muted text-uppercase">Urgent</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
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

    /* Card enhancements */
    .card {
        transition: all 0.3s ease;
        border-radius: 1rem;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
    }

    /* Announcement specific card styling */
    .announcement-card {
        border-left: 8px solid transparent;
        /* Placeholder for type border */
        transition: border-left-color 0.3s ease;
    }

    .announcement-card:hover {
        border-left-color: var(--bs-primary);
        /* Default hover color */
    }

    .announcement-card .border-primary {
        border-left-color: var(--bs-primary) !important;
    }

    .announcement-card .border-success {
        border-left-color: var(--bs-success) !important;
    }

    .announcement-card .border-warning {
        border-left-color: var(--bs-warning) !important;
    }

    .announcement-card .border-danger {
        border-left-color: var(--bs-danger) !important;
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

    /* Media preview styling */
    .media-preview img,
    .media-preview video,
    .media-preview iframe {
        border-radius: 0.75rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        max-width: 100%;
        height: auto;
        object-fit: cover;
    }

    .media-preview iframe {
        min-height: 300px;
        /* Ensure PDF viewer has a decent height */
    }

    /* Badge styling */
    .badge {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Empty state styling */
    .empty-state-icon {
        font-size: 4rem;
        opacity: 0.3;
    }

    /* Statistics styling */
    .statistics-card .h4 {
        font-size: 2.25rem;
    }

    .statistics-card small {
        font-size: 0.85rem;
        letter-spacing: 0.5px;
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

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }

        .d-flex.gap-2 {
            justify-content: center;
        }

        .btn {
            font-size: 0.9rem;
        }

        .btn-sm {
            font-size: 0.75rem;
            padding: 0.2rem 0.4rem;
        }

        .media-preview iframe,
        .media-preview video {
            height: 250px !important;
        }

        .media-preview img {
            max-height: 200px !important;
        }

        .announcement-card {
            border-left: 4px solid transparent;
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

        .btn-sm {
            font-size: 0.7rem;
            padding: 0.15rem 0.3rem;
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
        }

        .display-4 {
            font-size: 2rem !important;
        }

        .media-preview iframe,
        .media-preview video {
            height: 200px !important;
        }

        .media-preview img {
            max-height: 150px !important;
        }

        .d-flex.gap-2 {
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

    /* For the statistics card */

    @keyframes cardEntrance {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush