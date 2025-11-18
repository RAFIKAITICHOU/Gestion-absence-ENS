@extends('layouts.etudiantMenu')

@section('breadcrumb', 'Mon QR Code')
@section('title', 'Mon QR Code')

@section('content')
<div class="container-fluid py-4">
    <!-- Header stylisé -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body bg-gradient-primary text-white py-4 px-4 rounded-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                            <i class="bi bi-qr-code fs-2 text-white"></i>
                        </div>
                        <div>
                            <h1 class="mb-0 fw-bold">Votre code QR personnel</h1>
                            <p class="mb-0 opacity-75">Utilisez ce code pour la prise de présence</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Section -->
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-4 text-center">
                    <!-- Student Info -->
                    <div class="mb-4 section-divider">
                        <h5 class="text-primary fw-bold mb-3"><i class="bi bi-person-badge me-2"></i>Informations étudiant</h5>
                        <div class="bg-light p-4 rounded-3 border">
                            <div class="row g-2">
                                <div class="col-12 col-sm-6 text-start">
                                    <strong class="text-muted">Nom :</strong> <span class="fw-semibold">{{ Auth::user()->name }}</span>
                                </div>
                                <div class="col-12 col-sm-6 text-start">
                                    <strong class="text-muted">Prénom :</strong> <span class="fw-semibold">{{ Auth::user()->prenom }}</span>
                                </div>
                                <div class="col-12 text-start mt-3">
                                    <strong class="text-muted">CNE :</strong> <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill ms-2">{{ Auth::user()->etudiant->cne }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code Display -->
                    <div class="mb-4 section-divider">
                        <h5 class="text-secondary fw-bold mb-3"><i class="bi bi-qr-code-scan me-2"></i>Votre QR Code</h5>
                        <div class="d-flex justify-content-center">
                            <div class="bg-white p-4 border rounded-4 shadow-sm qr-code-container">
                                {!! QrCode::size(200)->generate(Auth::user()->etudiant->cne) !!}
                            </div>
                        </div>
                        <p class="text-muted mt-3 small"><i class="bi bi-info-circle me-1"></i>
                            Présentez ce QR Code lors de la prise de présence</p>
                    </div>

                    <!-- Download Buttons -->
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <a href="{{ route('etudiant.qr.png') }}" class="btn btn-outline-primary w-100 btn-lg rounded-pill shadow-sm">
                                <i class="bi bi-download me-2"></i>Télécharger PNG
                            </a>
                        </div>
                        <div class="col-12 col-sm-6">
                            <a href="{{ route('etudiant.qr.pdf') }}" class="btn btn-danger w-100 btn-lg rounded-pill shadow-sm">
                                <i class="bi bi-file-earmark-pdf me-2"></i>Télécharger PDF
                            </a>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="mt-4">
                        <div class="alert alert-info border-0 rounded-4 shadow-sm slideInDown">
                            <h6 class="alert-heading fw-bold text-info"><i class="bi bi-lightbulb me-2"></i>Instructions d'utilisation</h6>
                            <ul class="mb-0 text-start small">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Gardez ce QR Code accessible sur votre téléphone</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Présentez-le au professeur lors de chaque cours</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Assurez-vous que le code soit bien visible et lisible</li>
                                <li><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>En cas de problème, contactez l'administration</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="row justify-content-center mt-4">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-4">
                    <div class="row text-center g-3">
                        <div class="col-4">
                            <div class="text-primary icon-feature">
                                <i class="bi bi-shield-check fs-2 mb-2"></i>
                                <div class="fw-semibold">Sécurisé</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-success icon-feature">
                                <i class="bi bi-lightning-charge fs-2 mb-2"></i>
                                <div class="fw-semibold">Rapide</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-info icon-feature">
                                <i class="bi bi-phone fs-2 mb-2"></i>
                                <div class="fw-semibold">Mobile</div>
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

    .canvas-container {
        min-height: 220px;
        border: 1px solid #e9ecef;
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }

    .alert {
        border-left: 5px solid #0dcaf0;
        animation: slideInDown 0.5s ease-out;
        padding: 1.5rem;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .badge {
        font-weight: 600;
    }

    .btn {
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }

        .btn-lg {
            font-size: 0.9rem;
            padding: 0.6rem 1rem;
        }

        .canvas-container {
            min-height: 180px;
        }
    }
</style>
@endpush