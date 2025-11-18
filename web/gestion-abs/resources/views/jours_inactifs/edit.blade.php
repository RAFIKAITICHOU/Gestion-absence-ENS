@extends('layouts.adminMenu')

@section('title', 'Modifier un jour inactif')
@section('breadcrumb', 'Modifier un jour inactif')

@section('content')
<div class="container-fluid py-4">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-body bg-gradient-pink text-white py-4 px-4 rounded">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                                <i class="bi bi-calendar-x fs-2 text-white"></i>
                            </div>
                            <div>
                                <h1 class="mb-0 fw-bold">Modifier un jour inactif</h1>
                                <p class="mb-0 opacity-75">Mettez à jour les informations de la période d'inactivité</p>
                            </div>
                        </div>
                        <div class="d-none d-md-block">
                            <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-calendar-event fs-1 text-white opacity-50"></i>
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
                <div class="card-body p-4">
                    <form action="{{ route('jours_inactifs.update', $jour->id) }}" method="POST" id="jourInactifForm">
                        @csrf
                        @method('PUT')

                        <!-- Motif -->
                        <div class="section-divider mb-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-exclamation-circle text-danger"></i>
                                </div>
                                <h6 class="mb-0 fw-bold text-danger">Motif</h6>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="titre" class="form-label fw-semibold text-dark">
                                <i class="bi bi-pen text-primary me-1"></i>Motif de l'inactivité
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="titre" id="titre"
                                value="{{ old('titre', $jour->titre) }}"
                                class="form-control @error('titre') is-invalid @enderror"
                                placeholder="Ex: Vacances, Jour férié..." required>
                            @error('titre')
                            <div class="invalid-feedback d-block"><i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dates -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="date_debut" class="form-label fw-semibold text-dark">
                                    <i class="bi bi-calendar text-success me-1"></i>Date de début<span class="text-danger">*</span>
                                </label>
                                <input type="date" name="date_debut" id="date_debut"
                                    value="{{ old('date_debut', $jour->date_debut) }}"
                                    class="form-control @error('date_debut') is-invalid @enderror" required>
                                @error('date_debut')
                                <div class="invalid-feedback d-block"><i class="bi bi-x-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="date_fin" class="form-label fw-semibold text-dark">
                                    <i class="bi bi-calendar-check text-info me-1"></i>Date de fin <small class="text-muted">(optionnel)</small>
                                </label>
                                <input type="date" name="date_fin" id="date_fin"
                                    value="{{ old('date_fin', $jour->date_fin) }}"
                                    class="form-control @error('date_fin') is-invalid @enderror">
                                @error('date_fin')
                                <div class="invalid-feedback d-block"><i class="bi bi-x-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Durée calculée -->
                        <div class="alert alert-info d-flex align-items-center mb-4" role="alert" id="dureeAlert" style="display: none;">
                            <i class="bi bi-hourglass-split me-2"></i>
                            <div><strong id="dureeText"></strong></div>
                        </div>

                        <!-- Infos dates -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body p-3 d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="bi bi-calendar-plus text-success"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Créé le</h6>
                                            <small class="text-muted">{{ $jour->created_at->format('d/m/Y à H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body p-3 d-flex align-items-center">
                                        <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="bi bi-clock-history text-warning"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Dernière modification</h6>
                                            <small class="text-muted">{{ $jour->updated_at->format('d/m/Y à H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Impact EDT -->
                        <div class="alert alert-warning border-warning d-flex align-items-start mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-3 mt-1 text-warning"></i>
                            <div>
                                <strong class="text-warning">Impact sur l'emploi du temps</strong>
                                <p class="mb-2 text-muted small">Les cours pendant cette période seront automatiquement annulés.</p>
                                <span class="badge bg-warning text-dark me-2"><i class="bi bi-calendar-x me-1"></i> Cours suspendus</span>
                                <span class="badge bg-info text-white"><i class="bi bi-bell-fill me-1"></i> Notifications envoyées</span>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex flex-column flex-sm-row gap-2 pt-4 border-top mt-4">
                            <button type="submit" class="btn btn-pink btn-lg rounded-pill px-4">
                                <i class="bi bi-check-circle me-2"></i>Enregistrer
                                <div class="spinner-border spinner-border-sm ms-2 d-none" role="status" id="loadingSpinner"></div>
                            </button>
                            <a href="{{ route('dashboardadmin.GestionEDT') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                                <i class="bi bi-x-circle me-2"></i>Annuler
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
    .bg-gradient-pink {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
    }

    .btn-pink {
        background-color: #f5576c;
        color: #fff;
        border: none;
    }

    .btn-pink:hover {
        background-color: #d94c60;
        color: #fff;
    }

    .section-divider {
        padding-left: 1rem;
        border-left: 4px solid #f5576c;
        margin-bottom: 1rem;
    }

    .form-control:focus {
        border-color: #f5576c;
        box-shadow: 0 0 0 0.2rem rgba(245, 87, 108, 0.25);
    }

    .card {
        opacity: 0;
        transform: translateY(20px);
        animation: cardFade 0.6s ease forwards;
    }

    @keyframes cardFade {
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
        const dateDebut = document.getElementById('date_debut');
        const dateFin = document.getElementById('date_fin');
        const dureeAlert = document.getElementById('dureeAlert');
        const dureeText = document.getElementById('dureeText');
        const spinner = document.getElementById('loadingSpinner');

        function updateDuration() {
            const d1 = new Date(dateDebut.value);
            const d2 = new Date(dateFin.value);
            if (dateDebut.value) {
                if (dateFin.value && d2 >= d1) {
                    const diff = Math.ceil((d2 - d1) / (1000 * 60 * 60 * 24)) + 1;
                    dureeText.innerText = `${diff} jour${diff > 1 ? 's' : ''} (${d1.toLocaleDateString('fr-FR')} → ${d2.toLocaleDateString('fr-FR')})`;
                    dureeAlert.style.display = 'flex';
                } else if (!dateFin.value) {
                    dureeText.innerText = `1 jour (${d1.toLocaleDateString('fr-FR')})`;
                    dureeAlert.style.display = 'flex';
                } else {
                    dureeAlert.style.display = 'none';
                }
            } else {
                dureeAlert.style.display = 'none';
            }
        }

        dateDebut.addEventListener('change', updateDuration);
        dateFin.addEventListener('change', updateDuration);
        updateDuration();

        document.getElementById('jourInactifForm').addEventListener('submit', function() {
            spinner.classList.remove('d-none');
        });
    });
</script>
@endpush