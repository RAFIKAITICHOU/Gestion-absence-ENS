@extends('layouts.' . $menu)
@section('title', 'Liste des présences')
@section('breadcrumb', 'Listes des présences')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-primary text-white rounded-4">
                <div class="card-body py-4 px-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1 fw-bold">
                                <i class="bi bi-clipboard-check me-3"></i>Liste des Présences
                            </h2>
                            <p class="mb-0 opacity-75">Consultation et gestion des absences étudiantes</p>
                        </div>
                        <div class="d-none d-md-block">
                            <i class="bi bi-people-fill display-4 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                    <div>
                        <strong>Succès !</strong> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-light border-0 rounded-top-4 py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-funnel me-2"></i>Filtres de recherche
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('liste.presences') }}" id="filterForm">
                        <div class="row g-4">
                            <div class="col-12 col-md-6 col-lg-3">
                                <label for="filiere" class="form-label fw-semibold">
                                    <i class="bi bi-diagram-3 me-1"></i>Filière
                                </label>
                                <select name="filiere_id" id="filiere" class="form-select form-select-lg rounded-3 border-2" onchange="this.form.submit()">
                                    <option value="">-- Toutes les filières --</option>
                                    @foreach($filieres as $f)
                                    <option value="{{ $f->id }}" {{ request('filiere_id') == $f->id ? 'selected' : '' }}>
                                        {{ $f->nom_filiere }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <label for="groupe" class="form-label fw-semibold">
                                    <i class="bi bi-people me-1"></i>Groupe
                                </label>
                                <select name="groupe_id" id="groupe" class="form-select form-select-lg rounded-3 border-2" onchange="this.form.submit()">
                                    <option value="">-- Tous les groupes --</option>
                                    @foreach($groupes as $g)
                                    <option value="{{ $g->id }}" {{ request('groupe_id') == $g->id ? 'selected' : '' }}>
                                        {{ $g->nom_groupe }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <label for="date" class="form-label fw-semibold">
                                    <i class="bi bi-calendar me-1"></i>Date (optionnelle)
                                </label>
                                <input type="date" name="date" id="date" value="{{ request('date') }}"
                                    class="form-control form-control-lg rounded-3 border-2" onchange="this.form.submit()">
                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-gear me-1"></i>Options
                                </label>
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="just_absents" id="just_absents"
                                            class="form-check-input" onchange="this.form.submit()"
                                            {{ request('just_absents') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium" for="just_absents">
                                            Uniquement absents
                                        </label>
                                    </div>
                                    <a href="{{ route('liste.presences') }}" class="btn btn-outline-secondary rounded-pill">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats and Export Section -->
    @if($etudiants->count())
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="badge bg-primary fs-6 px-3 py-2 rounded-pill">
                        <i class="bi bi-people-fill me-2"></i>{{ $etudiants->count() }} étudiant(s) trouvé(s)
                    </div>
                    @php
                    $totalAbsences = $etudiants->sum(function($etudiant) {
                    return $etudiant->filteredPresences ? $etudiant->filteredPresences->count() : 0;
                    });
                    @endphp
                    <div class="badge bg-danger fs-6 px-3 py-2 rounded-pill">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $totalAbsences }} absence(s) totale(s)
                    </div>
                </div>
                <a href="{{ route('presences.export.pdf', [
                    'filiere_id' => request('filiere_id'),
                    'groupe_id' => request('groupe_id'),
                    'date' => request('date'),
                    'non_justifiees' => request('non_justifiees'),
                    'just_absents' => request('just_absents'),
                ]) }}" target="_blank" class="btn btn-danger btn-lg rounded-pill shadow-sm">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Exporter PDF
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Table Section -->
    <div class="row">
        <div class="col-12">
            @if($etudiants->count())
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-light border-0 rounded-top-4 py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-table me-2"></i>Détail des présences
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 modern-table">
                            <thead class="table-dark">
                                <tr>
                                    <th class="px-4 py-3 fw-bold">N°</th>
                                    <th class="px-4 py-3 fw-bold">Étudiant</th>
                                    <th class="px-4 py-3 fw-bold text-center">Absences</th>
                                    <th class="px-4 py-3 fw-bold">Date</th>
                                    <th class="px-4 py-3 fw-bold">Cours</th>
                                    <th class="px-4 py-3 fw-bold">Horaire</th>
                                    <th class="px-4 py-3 fw-bold">Salle</th>
                                    <th class="px-4 py-3 fw-bold text-center">État</th>
                                    <th class="px-4 py-3 fw-bold text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $row = 1; @endphp
                                @foreach($etudiants as $etudiant)
                                @php
                                $absences = $etudiant->filteredPresences ?? collect();
                                $absencesCount = $absences->count();
                                $first = true;
                                @endphp
                                @if($absencesCount > 0)
                                @foreach($absences as $presence)
                                <tr class="table-row-hover">
                                    @if($first)
                                    <td rowspan="{{ $absencesCount }}" class="px-4 py-3 fw-bold text-primary">
                                        {{ $row++ }}
                                    </td>
                                    <td rowspan="{{ $absencesCount }}" class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                {{ substr($etudiant->user->prenom, 0, 1) }}{{ substr($etudiant->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $etudiant->user->prenom }} {{ $etudiant->user->name }}</div>
                                                <small class="text-muted">{{ $etudiant->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td rowspan="{{ $absencesCount }}" class="px-4 py-3 text-center">
                                        <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill">{{ $absencesCount }}</span>
                                    </td>
                                    @php $first = false; @endphp
                                    @endif
                                    <td class="px-4 py-3">
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-calendar me-1"></i>{{ $presence->session->date ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="fw-semibold text-dark">{{ $presence->session->cours->nom ?? '—' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-info text-white">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $presence->session->heure_debut ?? '--:--' }} - {{ $presence->session->heure_fin ?? '--:--' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-building me-1"></i>{{ $presence->session->salle->nom ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($presence->justification)
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                                <i class="bi bi-check-circle me-1"></i>Justifié
                                            </span>
                                            @if($presence->justificatif_fichier)
                                            <i class="bi bi-paperclip text-primary fs-5" title="Fichier joint"></i>
                                            @endif
                                        </div>
                                        @else
                                        <span class="badge bg-danger fs-6 px-3 py-2">
                                            <i class="bi bi-x-circle me-1"></i>Non justifié
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="btn-group" role="group">
                                            @if(!$presence->justification)
                                            <!-- Justification simple -->
                                            <form method="POST" action="{{ route('presences.justifier', $presence->id) }}" class="d-inline">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm rounded-pill me-1" title="Justifier">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <!-- Justification avec fichier -->
                                            <button type="button" class="btn btn-primary btn-sm rounded-pill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#justificationModal{{ $presence->id }}" title="Justifier avec fichier">
                                                <i class="bi bi-file-earmark-plus"></i>
                                            </button>
                                            @else
                                            <!-- Actions pour absence justifiée -->
                                            @if($presence->justificatif_fichier)
                                            <button type="button" class="btn btn-info btn-sm rounded-pill me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewFileModal{{ $presence->id }}" title="Voir le fichier">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            @endif
                                            <form method="POST" action="{{ route('presences.nonjustifier', $presence->id) }}" class="d-inline">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill" title="Annuler la justification">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                @php
                                $retards = $etudiant->presences->filter(fn($p) => $p->etat === 1 && $p->retard > 0);
                                @endphp
                                @if($retards->count())
                                <tr class="table-row-hover">
                                    <td class="px-4 py-3 fw-bold text-primary">{{ $row++ }}</td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                {{ substr($etudiant->user->prenom, 0, 1) }}{{ substr($etudiant->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $etudiant->user->prenom }} {{ $etudiant->user->name }}</div>
                                                <small class="text-muted">{{ $etudiant->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-success fs-6 px-3 py-2 rounded-pill">0</span>
                                    </td>
                                    @php $retard = $retards->first(); @endphp
                                    <td class="px-4 py-3">
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-calendar me-1"></i>{{ $retard->session->date ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="fw-semibold text-dark">{{ $retard->session->cours->nom ?? '—' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-info text-white">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $retard->session->heure_debut ?? '--:--' }} - {{ $retard->session->heure_fin ?? '--:--' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-building me-1"></i>{{ $retard->session->salle->nom ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                            <i class="bi bi-clock-history me-1"></i>Retard {{ $retard->retard }} min
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center text-muted">—</td>
                                </tr>
                                @else
                                <tr class="table-row-hover">
                                    <td class="px-4 py-3 fw-bold text-primary">{{ $row++ }}</td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                {{ substr($etudiant->user->prenom, 0, 1) }}{{ substr($etudiant->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $etudiant->user->prenom }} {{ $etudiant->user->name }}</div>
                                                <small class="text-muted">{{ $etudiant->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-success fs-6 px-3 py-2 rounded-pill">0</span>
                                    </td>
                                    <td colspan="6" class="px-4 py-3 text-center">
                                        <div class="text-muted">
                                            <i class="bi bi-check-circle me-2"></i>Aucune absence ni retard enregistré
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-search display-1 text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">Aucun étudiant trouvé</h4>
                    <p class="text-muted mb-4">Veuillez ajuster vos filtres de recherche pour afficher les résultats.</p>
                    <a href="{{ route('liste.presences') }}" class="btn btn-primary rounded-pill">
                        <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser les filtres
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals pour justification avec fichier -->
@foreach($etudiants as $etudiant)
@if($etudiant->filteredPresences)
@foreach($etudiant->filteredPresences as $presence)
@if(!$presence->justification)
<!-- Modal de justification avec fichier -->
<div class="modal fade" id="justificationModal{{ $presence->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-file-earmark-plus me-2"></i>Justifier l'absence
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('presences.justifier.fichier', $presence->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Étudiant</label>
                            <input type="text" class="form-control rounded-3"
                                value="{{ $etudiant->user->prenom }} {{ $etudiant->user->name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cours</label>
                            <input type="text" class="form-control rounded-3"
                                value="{{ $presence->session->cours->nom ?? '—' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date</label>
                            <input type="text" class="form-control rounded-3"
                                value="{{ $presence->session->date ?? '—' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Horaire</label>
                            <input type="text" class="form-control rounded-3"
                                value="{{ $presence->session->heure_debut ?? '--:--' }} - {{ $presence->session->heure_fin ?? '--:--' }}" readonly>
                        </div>
                        <div class="col-12">
                            <label for="justification_text{{ $presence->id }}" class="form-label fw-semibold">
                                Motif de justification <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control rounded-3" id="justification_text{{ $presence->id }}"
                                name="justification_text" rows="4" required
                                placeholder="Saisissez le motif détaillé de l'absence..."></textarea>
                        </div>
                        <div class="col-12">
                            <label for="justification_file{{ $presence->id }}" class="form-label fw-semibold">
                                Fichier justificatif (optionnel)
                            </label>
                            <input type="file" class="form-control rounded-3" id="justification_file{{ $presence->id }}"
                                name="justification_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Formats acceptés: PDF, JPG, PNG, DOC, DOCX (max 5MB)
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-check-lg me-2"></i>Justifier l'absence
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if($presence->justification && $presence->justificatif_fichier)
<!-- Modal de visualisation du fichier -->
<div class="modal fade" id="viewFileModal{{ $presence->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-info text-white border-0 rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-file-earmark-text me-2"></i>Fichier de justification
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <strong>Étudiant:</strong><br>
                        <span class="text-muted">{{ $etudiant->user->prenom }} {{ $etudiant->user->name }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Cours:</strong><br>
                        <span class="text-muted">{{ $presence->session->cours->nom ?? '—' }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Date:</strong><br>
                        <span class="text-muted">{{ $presence->session->date ?? '—' }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Justification:</strong><br>
                        <span class="text-muted">{{ $presence->justification }}</span>
                    </div>
                </div>

                <div class="text-center border rounded-4 p-4">
                    @php
                    $extension = pathinfo($presence->justificatif_fichier, PATHINFO_EXTENSION);
                    @endphp
                    @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                    <img src="{{ asset('storage/' . $presence->justificatif_fichier) }}"
                        class="img-fluid rounded-3 shadow" style="max-height: 500px;" alt="Justificatif">
                    @elseif(strtolower($extension) === 'pdf')
                    <iframe src="{{ asset('storage/' . $presence->justificatif_fichier) }}"
                        width="100%" height="500px" frameborder="0" class="rounded-3">
                        <p>Votre navigateur ne supporte pas l'affichage des PDF.
                            <a href="{{ route('presences.fichier.telecharger', $presence->id) }}">Télécharger le fichier</a>
                        </p>
                    </iframe>
                    @else
                    <div class="alert alert-info border-0 rounded-3">
                        <i class="bi bi-file-earmark-text display-4 text-info"></i>
                        <h5 class="mt-3">{{ basename($presence->justificatif_fichier) }}</h5>
                        <p class="mb-0">Ce type de fichier ne peut pas être prévisualisé.</p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <a href="{{ route('presences.fichier.telecharger', $presence->id) }}"
                    class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-download me-2"></i>Télécharger
                </a>
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-2"></i>Fermer
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
@endif
@endforeach
@endsection

@push('styles')
<style>
    /* Variables CSS */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --shadow-light: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        --shadow-medium: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        --shadow-heavy: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        --border-radius: 1rem;
    }

    /* Background gradient pour le header */
    .bg-gradient-primary {
        background: var(--primary-gradient) !important;
    }

    /* Table moderne */
    .modern-table {
        font-size: 0.95rem;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table thead th {
        font-weight: 700;
        letter-spacing: 0.5px;
        border: none;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .modern-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f1f3f4;
    }

    .table-row-hover:hover {
        background-color: #f8f9fa !important;
        transform: translateY(-1px);
        box-shadow: var(--shadow-light);
    }

    /* Avatar circle */
    .avatar-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.9rem;
        text-transform: uppercase;
        box-shadow: var(--shadow-light);
    }

    /* Badges améliorés */
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
        box-shadow: var(--shadow-light);
    }

    .badge.fs-6 {
        font-size: 0.875rem !important;
        padding: 0.5rem 0.75rem !important;
    }

    /* Boutons améliorés */
    .btn {
        font-weight: 500;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-light);
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }

    /* Cards améliorées */
    .card {
        transition: all 0.3s ease;
        border: none !important;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-heavy) !important;
    }

    /* Form controls */
    .form-control,
    .form-select {
        border-width: 2px;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        transform: translateY(-1px);
    }

    /* Form switches */
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    /* Modals */
    .modal-content {
        border: none;
        overflow: hidden;
    }

    .modal-header {
        border-bottom: none;
        padding: 1.5rem 2rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border-top: none;
        padding: 1.5rem 2rem;
    }

    /* Alerts */
    .alert {
        border: none;
        font-weight: 500;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .modern-table {
            font-size: 0.9rem;
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 992px) {
        .modern-table {
            font-size: 0.85rem;
        }

        .px-4 {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        .py-3 {
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
        }
    }

    @media (max-width: 768px) {
        .modern-table {
            font-size: 0.8rem;
        }

        .avatar-circle {
            width: 35px;
            height: 35px;
            font-size: 0.75rem;
        }

        .badge.fs-6 {
            font-size: 0.75rem !important;
            padding: 0.4rem 0.6rem !important;
        }

        .btn-sm {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .px-4 {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }
    }

    @media (max-width: 576px) {
        .modern-table {
            font-size: 0.75rem;
        }

        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .card-body {
            padding: 1.5rem !important;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-header,
        .modal-footer {
            padding: 1rem 1.5rem;
        }
    }

    /* Animation pour le chargement */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card,
    .alert {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Scrollbar personnalisée */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Loading states */
    .btn:disabled {
        opacity: 0.6;
        transform: none !important;
    }

    /* Print styles */
    @media print {

        .btn,
        .modal,
        .alert-dismissible .btn-close {
            display: none !important;
        }

        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
        }

        .table {
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des badges au chargement
        const badges = document.querySelectorAll('.badge');
        badges.forEach((badge, index) => {
            badge.style.animationDelay = `${index * 0.1}s`;
            badge.classList.add('animate__animated', 'animate__fadeInUp');
        });

        // Confirmation avant soumission des formulaires de justification
        const forms = document.querySelectorAll('form[action*="justifier"]');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Traitement...';
                    submitBtn.disabled = true;
                }
            });
        });

        // Auto-submit des filtres avec délai
        let filterTimeout;
        const filterInputs = document.querySelectorAll('#filterForm input, #filterForm select');
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(() => {
                    this.form.submit();
                }, 300);
            });
        });

        // Tooltip pour les boutons d'action
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush