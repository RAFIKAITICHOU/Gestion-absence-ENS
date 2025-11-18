@extends('layouts.etudiantMenu')

@section('breadcrumb', 'Tableau de bord')
@section('title', 'Tableau de bord')

@section('content')
<div class="container-fluid py-4">
  <!-- En-tête stylisé -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body bg-gradient-primary text-white py-4 px-4 rounded-4">
          <div class="d-flex align-items-center">
            <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
              <i class="bi bi-speedometer2 fs-2 text-white"></i>
            </div>
            <div>
              <h1 class="mb-0 fw-bold">Bienvenue de nouveau, <strong>{{ Auth::user()->prenom }} {{ Auth::user()->name }}</strong> ! 👋</h1>
              <p class="mb-0 opacity-75">Tableau de bord étudiant</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Alerte importante stylisée -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="alert alert-warning border-0 rounded-4 shadow-sm fade show" role="alert">
        <div class="d-flex align-items-start">
          <div class="bg-warning bg-opacity-20 rounded-circle p-2 me-3 flex-shrink-0">
            <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
          </div>
          <div>
            <strong class="text-warning">Remarque importante :</strong><br>
            <span class="text-dark">
              Toutes les absences doivent être <strong>justifiées auprès de l'administration</strong> dans les délais impartis.
              Des absences non justifiées peuvent entraîner des sanctions, voire une exclusion temporaire ou définitive de l'établissement.
            </span>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  </div>

  <!-- Graphique des absences -->
  <div class="row">
    <div class="col-12">
      <div class="card mt-2 shadow-lg border-0 rounded-4">
        <!-- En-tête de la carte -->
        <div class="card-header bg-gradient-info text-white border-0 rounded-top-4 py-4">
          <h5 class="card-title mb-0 fw-bold"><i class="bi bi-graph-up me-2"></i>Mes absences par matière</h5>
        </div>
        <div class="card-body p-4">
          <div class="canvas-container bg-light rounded-3 p-3" style="position: relative; width: 100%; max-width: 100%;">
            @if(isset($chart) && $chart->labels && count($chart->labels) > 0)
            {!! $chart->container() !!}
            @else
            <div class="text-center py-5">
              <div class="mb-3">
                <i class="bi bi-graph-up display-1 text-muted opacity-50"></i>
              </div>
              <p class="text-muted fs-5 fw-medium">Aucune donnée à afficher.</p>
              <small class="text-muted">Les données d'absence apparaîtront ici une fois disponibles.</small>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  /* Custom gradient for primary elements */
  .bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  }

  .bg-gradient-info {
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%) !important;
  }

  /* Card improvements */
  .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
  }

  /* Alert improvements */
  .alert {
    border-left: 4px solid var(--bs-warning) !important;
    /* Consistent border for warning */
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

  /* Chart container styling */
  .canvas-container {
    min-height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa !important;
    /* Light background for chart area */
    border: 1px solid #e9ecef;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .card-body {
      padding: 1.5rem !important;
    }

    .alert {
      padding: 1rem;
    }

    .alert .fs-5 {
      font-size: 1.25rem !important;
    }

    .alert strong {
      font-size: 0.9rem;
    }

    .alert span {
      font-size: 0.85rem;
    }

    .canvas-container {
      min-height: 250px;
    }
  }
</style>
@endpush

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@if(isset($chart) && $chart->labels && count($chart->labels) > 0)
<style>
  .canvas-container canvas {
    width: 100% !important;
    height: auto !important;
  }
</style>

{!! $chart->script() !!}
@endif
@endsection