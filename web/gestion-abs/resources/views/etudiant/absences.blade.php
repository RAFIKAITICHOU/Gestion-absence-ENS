@extends('layouts.etudiantMenu')

@section('breadcrumb', 'Mes Absences')
@section('title', 'Mes Absences')

@section('content')
<div class="container-fluid py-4">
  <!-- Header stylisé -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body bg-gradient-primary text-white py-4 px-4 rounded-4">
          <div class="d-flex align-items-center">
            <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
              <i class="bi bi-calendar-x fs-2 text-white"></i>
            </div>
            <div>
              <h1 class="mb-0 fw-bold">Mes absences enregistrées</h1>
              <p class="mb-0 opacity-75">Suivez votre assiduité et vos justifications</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @php
  $absences = $presences->filter(fn($p) => $p->etat === 0);
  $totalAbsences = $absences->count();
  $absencesJustifiees = $absences->filter(fn($p) => $p->justification)->count();
  $absencesNonJustifiees = $totalAbsences - $absencesJustifiees;
  $retards = $presences->filter(fn($p) => $p->etat === 1 && $p->retard > 0);
  @endphp

  <!-- Statistiques -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-4">
          <h5 class="text-muted fw-bold mb-3"><i class="bi bi-bar-chart-line me-2"></i>Statistiques d'absences</h5>
          <div class="row text-center g-4">
            <div class="col-4">
              <div class="stat-item text-primary bg-light p-3 rounded-3 shadow-sm">
                <i class="bi bi-calendar-x fs-3 mb-2"></i>
                <h4 class="fw-bold mb-0">{{ $totalAbsences }}</h4>
                <small class="text-muted">Total</small>
              </div>
            </div>
            <div class="col-4">
              <div class="stat-item text-warning bg-light p-3 rounded-3 shadow-sm">
                <i class="bi bi-check-circle fs-3 mb-2"></i>
                <h4 class="fw-bold mb-0">{{ $absencesJustifiees }}</h4>
                <small class="text-muted">Justifiées</small>
              </div>
            </div>
            <div class="col-4">
              <div class="stat-item text-danger bg-light p-3 rounded-3 shadow-sm">
                <i class="bi bi-x-circle fs-3 mb-2"></i>
                <h4 class="fw-bold mb-0">{{ $absencesNonJustifiees }}</h4>
                <small class="text-muted">Non justifiées</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bouton export PDF -->
  <div class="row mb-4">
    <div class="col-12 text-end">
      <a href="{{ route('etudiant.absences.pdf') }}" class="btn btn-danger btn-lg rounded-pill shadow-sm">
        <i class="bi bi-file-earmark-pdf-fill me-2"></i> Télécharger le rapport PDF
      </a>
    </div>
  </div>

  <!-- Liste des absences -->
  <div class="row mb-4">
    <div class="col-12">
      @if($absences->isEmpty())
      <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body text-center py-5">
          <i class="bi bi-check-circle-fill display-4 text-success mb-3"></i>
          <h5 class="fw-bold">Aucune absence enregistrée</h5>
          <p class="text-muted mb-0">Continuez ainsi ! Votre assiduité est remarquable.</p>
        </div>
      </div>
      @else
      <div class="card border-0 shadow-lg rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4 py-3">
          <h5 class="fw-bold mb-0"><i class="bi bi-list-ul me-2"></i>Détail des absences</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="px-4 py-3 text-muted">Cours</th>
                  <th class="px-4 py-3 text-muted">Date</th>
                  <th class="px-4 py-3 text-muted">Heure</th>
                  <th class="px-4 py-3 text-center text-muted">État</th>
                </tr>
              </thead>
              <tbody>
                @foreach($absences as $presence)
                <tr>
                  <td class="px-4 py-3"><strong>{{ $presence->session->cours->nom ?? 'Inconnu' }}</strong></td>
                  <td class="px-4 py-3">{{ \Carbon\Carbon::parse($presence->session->date)->translatedFormat('d M Y') }}</td>
                  <td class="px-4 py-3"><small class="text-muted">{{ $presence->session->heure_debut }} - {{ $presence->session->heure_fin }}</small></td>
                  <td class="px-4 py-3 text-center">
                    @if($presence->justification)
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-check-circle-fill me-1"></i>Justifié</span>
                    @else
                    <span class="badge bg-danger rounded-pill px-3 py-2"><i class="bi bi-x-circle-fill me-1"></i>Non justifié</span>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>

  <!-- Liste des retards -->
  @if(!$retards->isEmpty())
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-lg rounded-4">
        <div class="card-header bg-warning text-dark rounded-top-4 py-3">
          <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2"></i>Mes retards</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="px-4 py-3 text-muted">Cours</th>
                  <th class="px-4 py-3 text-muted">Date</th>
                  <th class="px-4 py-3 text-muted">Heure</th>
                  <th class="px-4 py-3 text-center text-muted">Retard</th>
                </tr>
              </thead>
              <tbody>
                @foreach($retards as $presence)
                <tr>
                  <td class="px-4 py-3"><strong>{{ $presence->session->cours->nom ?? 'Inconnu' }}</strong></td>
                  <td class="px-4 py-3">{{ \Carbon\Carbon::parse($presence->session->date)->translatedFormat('d M Y') }}</td>
                  <td class="px-4 py-3"><small class="text-muted">{{ $presence->session->heure_debut }} - {{ $presence->session->heure_fin }}</small></td>
                  <td class="px-4 py-3 text-center">
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-clock-history me-1"></i>{{ $presence->retard }} min</span>
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
  @endif

  <!-- Alertes importantes -->
  @if(!$absences->isEmpty())
  <div class="row g-3">
    <div class="col-md-6">
      <div class="alert alert-danger border-0 rounded-4 shadow-sm" role="alert">
        <div class="d-flex align-items-start">
          <div class="bg-danger bg-opacity-25 rounded-circle p-2 me-3 flex-shrink-0">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
          </div>
          <div>
            <strong class="text-danger">Seuil d'absences :</strong><br>
            <span class="text-dark">Au-delà de <strong>20%</strong> d’absences, l’accès à l’examen peut être refusé.</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="alert alert-warning border-0 rounded-4 shadow-sm" role="alert">
        <div class="d-flex align-items-start">
          <div class="bg-warning bg-opacity-25 rounded-circle p-2 me-3 flex-shrink-0">
            <i class="bi bi-info-circle-fill fs-5 text-warning"></i>
          </div>
          <div>
            <strong class="text-warning">Justification :</strong><br>
            <span class="text-dark">Toute absence doit être <strong>justifiée auprès de l'administration</strong>.</span>
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
  .bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  }

  .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
  }

  .alert {
    border-left: 5px solid;
    animation: slideInDown 0.5s ease-out;
    padding: 1.5rem;
  }

  .stat-item {
    transition: all 0.3s ease;
  }

  .stat-item:hover {
    background-color: rgba(13, 110, 253, 0.05);
    transform: translateY(-2px);
  }

  .table thead th {
    font-weight: 600;
    color: #6c757d;
    border-bottom: 2px solid #e9ecef;
  }

  .table tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.03);
  }

  .badge {
    font-weight: 600;
    font-size: 0.85rem;
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
</style>
@endpush