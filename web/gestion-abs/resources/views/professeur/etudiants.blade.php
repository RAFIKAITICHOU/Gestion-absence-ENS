@extends('layouts.profMenu')

@section('breadcrumb', 'Mes étudiants')
@section('title', 'Mes étudiants')

@section('content')
<div class="container-fluid py-3">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 bg-primary-gradient text-white card-animated" data-delay="0.1">
                <div class="card-body py-4 px-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                                <i class="fas fa-users fs-2 text-white"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold">Mes étudiants</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 card-animated" data-delay="0.2">
                <div class="card-body p-3">
                    <form method="POST" action="{{ route('prof.etudiants.filtrer') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold"><i class="fas fa-book me-1"></i>Cours</label>
                                <select name="cours_id" class="form-select" onchange="this.form.submit()" required>
                                    <option value="">-- Choisir un cours --</option>
                                    @foreach ($cours as $c)
                                    <option value="{{ $c->id }}" {{ $selectedCours == $c->id ? 'selected' : '' }}>
                                        {{ $c->nom }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold"><i class="fas fa-graduation-cap me-1"></i>Filière</label>
                                <select name="filiere_id" class="form-select" onchange="this.form.submit()" {{ $selectedCours ? '' : 'disabled' }}>
                                    <option value="">-- Choisir une filière --</option>
                                    @foreach ($filieres as $f)
                                    <option value="{{ $f->id }}" {{ $selectedFiliere == $f->id ? 'selected' : '' }}>
                                        {{ $f->nom_filiere ?? $f->nom }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold"><i class="fas fa-layer-group me-1"></i>Groupe</label>
                                <select name="groupe_id" class="form-select" onchange="this.form.submit()" {{ $selectedFiliere ? '' : 'disabled' }}>
                                    <option value="">-- Choisir un groupe --</option>
                                    @foreach ($groupes as $g)
                                    <option value="{{ $g->id }}" {{ $selectedGroupe == $g->id ? 'selected' : '' }}>
                                        {{ $g->nom_groupe ?? $g->nom }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    @if (isset($selectedCours, $selectedFiliere, $selectedGroupe) && $etudiants->count())
    <!-- Export and Stats Card -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 card-animated" data-delay="0.3">
                <div class="card-header bg-gradient-info text-white py-3 px-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-chart-bar me-2"></i>Statistiques et Export</h5>
                </div>
                <div class="card-body py-3 px-4 d-flex justify-content-between align-items-center">
                    <div class="text-muted"><i class="fas fa-info-circle me-1"></i><strong>{{ $etudiants->count() }}</strong> étudiant(s) trouvé(s)</div>
                    <a href="{{ route('prof.etudiants.pdf', [$selectedCours, $selectedFiliere, $selectedGroupe]) }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf me-1"></i>Exporter PDF
                    </a>
                    <a href="{{ route('prof.etudiants.absences.pdf', [$selectedCours, $selectedFiliere, $selectedGroupe]) }}" class="btn btn-outline-primary ms-2">
                        <i class="fas fa-file-alt me-1"></i>Exporter absences par module
                    </a>

                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 card-animated" data-delay="0.4">
                <div class="card-header bg-gradient-success text-white py-3 px-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-list-ul me-2"></i>
                        Liste des étudiants —
                        <span class="text-white-50">{{ $cours->firstWhere('id', $selectedCours)?->nom ?? '' }}</span>
                        |
                        <span class="text-white-50">{{ $filieres->firstWhere('id', $selectedFiliere)?->nom_filiere ?? '' }}</span>
                        |
                        <span class="text-white-50">{{ $groupes->firstWhere('id', $selectedGroupe)?->nom_groupe ?? '' }}</span>

                    </h5>

                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3 py-3 text-center" style="width: 60px;">N°</th>
                                    <th class="px-3 py-3"></th>
                                    <th class="px-3 py-3">Nom</th>
                                    <th class="px-3 py-3">Prénom</th>
                                    <th class="px-3 py-3">CNE</th>
                                    <th class="px-3 py-3">Date de naissance</th>
                                    <th class="px-3 py-3">Email</th>
                                    <th class="px-3 py-3 text-center">Absences</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($etudiants as $etudiant)
                                <tr>
                                    <td class="px-3 py-3 text-center"><span class="badge bg-primary rounded-circle" style="width: 30px; height: 30px; line-height: 20px;">
                                            {{ $loop->iteration }}</span></td>
                                    <td class="px-3 py-3">
                                        <div class="me-2">
                                            @if (!empty($etudiant->user->photo) && file_exists(storage_path('app/public/' . $etudiant->user->photo)))
                                            <img src="{{ Storage::url($etudiant->user->photo) }}"
                                                alt="Photo de {{ $etudiant->user->name }}"
                                                class="rounded-circle cursor-pointer"
                                                style="width: 40px; height: 40px; object-fit: cover;"
                                                onclick='showPhotoModal("{{ Storage::url($etudiant->user->photo) }}")'>
                                            @else
                                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; font-size: 0.8rem;">
                                                {{ strtoupper(substr($etudiant->user->name ?? '', 0, 1)) }}{{ strtoupper(substr($etudiant->user->prenom ?? '', 0, 1)) }}
                                            </div>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center"><strong>{{ $etudiant->user->name ?? '-' }}</strong></div>
                                    </td>
                                    <td class="px-3 py-3">{{ $etudiant->user->prenom ?? '-' }}</td>
                                    <td class="px-3 py-3"><span class="badge bg-primary">{{ $etudiant->cne }}</span></td>
                                    <td class="px-3 py-3">
                                        {{ $etudiant->user->date_naissance ? \Carbon\Carbon::parse($etudiant->user->date_naissance)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-3 py-3"><a href="mailto:{{ $etudiant->user->email ?? '' }}" class="text-decoration-none">{{ $etudiant->user->email ?? '-' }}</a></td>
                                    @php
                                    $nbAbsences = $etudiant->presences->where('etat', 0)->count();
                                    $badgeClass = $nbAbsences === 0 ? 'bg-success' : 'bg-danger';
                                    @endphp
                                    <td class="px-3 py-3 text-center">
                                        <span class="badge {{ $badgeClass }}">{{ $nbAbsences }}</span>
                                    </td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif (isset($selectedCours, $selectedFiliere, $selectedGroupe) && $etudiants->count() == 0)
    <!-- No Students Found -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 card-animated" data-delay="0.5">
                <div class="card-body text-center py-5">
                    <div class="text-muted"><i class="fas fa-inbox display-4 mb-3"></i>
                        <h5>Aucun étudiant trouvé</h5>
                        <p class="mb-0">Aucun étudiant n'est inscrit pour cette combinaison cours/filière/groupe.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Instructions -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 card-animated" data-delay="0.6">
                <div class="card-body text-center py-5">
                    <div class="text-primary"><i class="fas fa-filter display-4 mb-3"></i>
                        <h5>Sélectionnez vos critères</h5>
                        <p class="text-muted mb-0">
                            Choisissez un cours, une filière et un groupe pour afficher la liste des étudiants.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<!-- Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-body p-0">
                <img id="modalPhoto" src="" alt="Photo étudiante" class="img-fluid rounded-4 w-100">
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .cursor-pointer {
        cursor: pointer;
    }

    /* Custom gradient for primary elements */
    .bg-primary-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    /* Custom gradients for card headers (if distinct colors are desired) */
    .bg-gradient-info {
        background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #198754 0%, #146c43 100%);
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

    /* Button enhancements */
    .btn {
        transition: all 0.3s ease;
        font-weight: 500;
        border-radius: 0.5rem;
        /* Consistent border-radius for buttons */
    }

    .btn:hover {
        transform: translateY(-2px);
        /* Consistent lift */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn:active {
        transform: translateY(0);
    }

    /* Simple professor students page styles */
    .table {
        font-size: 0.9rem;
    }

    .table th {
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .badge {
        font-size: 0.8rem;
    }

    .badge.rounded-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    .form-select:disabled {
        background-color: #f8f9fa;
        opacity: 0.6;
    }

    .form-label {
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .card-header {
        border-bottom: none;
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }

    /* Avatar styling */
    .rounded-circle {
        font-weight: 600;
    }

    /* Email link styling */
    a[href^="mailto:"] {
        color: #0d6efd;
    }

    a[href^="mailto:"]:hover {
        color: #0a58ca;
        text-decoration: underline !important;
    }

    /* Form enhancements */
    .form-select:focus {
        border-color: #667eea;
        /* Consistent purple/blue focus */
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        /* Consistent purple/blue focus shadow */
    }

    .form-select option {
        padding: 0.5rem;
    }

    /* Loading state for selects */
    .form-select:disabled {
        cursor: not-allowed;
    }

    /* Success state for completed selection */
    .form-select:valid {
        border-color: #198754;
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
        .table {
            font-size: 0.8rem;
        }

        .px-3 {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }

        .py-3 {
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
        }

        .badge {
            font-size: 0.7rem;
        }

        .rounded-circle {
            width: 28px !important;
            height: 28px !important;
            font-size: 0.7rem !important;
        }

        .badge.rounded-circle {
            width: 25px !important;
            height: 25px !important;
            line-height: 15px !important;
        }

        .card-body {
            padding: 1rem !important;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }

        .d-flex.justify-content-between .btn {
            align-self: stretch;
        }
    }

    @media (max-width: 576px) {
        .table {
            font-size: 0.75rem;
        }

        .px-3 {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }

        .py-3 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        .badge {
            font-size: 0.65rem;
            padding: 0.25rem 0.5rem;
        }

        .rounded-circle {
            width: 25px !important;
            height: 25px !important;
            font-size: 0.65rem !important;
        }

        .badge.rounded-circle {
            width: 22px !important;
            height: 22px !important;
            line-height: 12px !important;
        }

        .display-4 {
            font-size: 2rem !important;
        }

        .btn {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
        }
    }

    /* Print styles */
    @media print {

        .btn,
        .card-header {
            display: none !important;
        }

        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
        }

        .bg-primary {
            background-color: #0d6efd !important;
            -webkit-print-color-adjust: exact;
        }

        .table {
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function showPhotoModal(photoUrl) {
        document.getElementById('modalPhoto').src = photoUrl;
        let modal = new bootstrap.Modal(document.getElementById('photoModal'));
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Smooth card entrance animation with delays
        const cards = document.querySelectorAll('.card-animated');
        cards.forEach((card) => {
            const delay = parseFloat(card.dataset.delay || '0');
            card.style.animationDelay = `${delay}s`;
        });

        // Auto-dismiss alerts after 5 seconds (if any alerts are added in the future)
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
    });
</script>
@endpush