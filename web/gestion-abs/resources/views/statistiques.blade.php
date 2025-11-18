@extends('layouts.' . $menu)
@section('breadcrumb', 'Statistiques')
@section('title', 'Statistiques des absences')

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
                <i class="bi bi-graph-up fs-2 text-white"></i>
              </div>
              <div>
                <h1 class="mb-0 fw-bold">Statistiques des absences</h1>
                <p class="mb-0 opacity-75">Analyse détaillée des présences et absences</p>
              </div>
            </div>
            <div class="d-none d-md-block">
              <div class="bg-white bg-opacity-10 rounded-3 p-3">
                <i class="bi bi-bar-chart fs-1 text-white opacity-50"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Filters Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
          <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
              <i class="bi bi-funnel text-primary"></i>
            </div>
            <h5 class="mb-0 fw-bold text-primary">Filtres et actions</h5>
          </div>
        </div>

        <div class="card-body p-4">
          <form method="GET" action="{{ route('statistiques') }}" class="row g-3">
            <div class="col-12 col-md-6 col-lg-3">
              <label for="filiere" class="form-label fw-semibold">
                <i class="bi bi-mortarboard me-1 text-primary"></i>Filière
              </label>
              <select name="filiere" id="filiere" class="form-select">
                <option value="">Toutes les filières</option>
                @foreach($filieres as $filiere)
                <option value="{{ $filiere->id }}" {{ $filiereFilter == $filiere->id ? 'selected' : '' }}>
                  {{ $filiere->nom_filiere }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
              <label for="mois" class="form-label fw-semibold">
                <i class="bi bi-calendar-month me-1 text-primary"></i>Mois
              </label>
              <select name="mois" id="mois" class="form-select">
                <option value="">Tous les mois</option>
                @for ($i = 1; $i <= 12; $i++)
                  <option value="{{ $i }}" {{ $moisFilter == $i ? 'selected' : '' }}>
                  {{ \Carbon\Carbon::create()->month($i)->locale('fr')->translatedFormat('F') }}
                  </option>
                  @endfor
              </select>
            </div>

            <div class="col-12 col-md-6 col-lg-2">
              <label class="form-label">&nbsp;</label>
              <button type="submit" class="btn btn-primary w-100 rounded-pill">
                <i class="bi bi-search me-1"></i>Filtrer
              </button>
            </div>

            <div class="col-12 col-md-6 col-lg-2">
              <label class="form-label">&nbsp;</label>
              <a href="{{ route('absences.export') }}" class="btn btn-success w-100 rounded-pill">
                <i class="bi bi-download me-1"></i>Export CSV
              </a>
            </div>

            <div class="col-12 col-lg-2">
              <label class="form-label">&nbsp;</label>
              <button type="button" onclick="window.print()" class="btn btn-outline-secondary w-100 rounded-pill">
                <i class="bi bi-printer me-1"></i>Imprimer
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts Section -->
  <div class="row g-4 mb-4">
    <!-- Chart by Filiere -->
    <div class="col-12 col-xl-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-light border-0 py-3">
          <div class="d-flex align-items-center">
            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
              <i class="bi bi-pie-chart text-info"></i>
            </div>
            <h5 class="mb-0 fw-bold text-info">Absences par filière</h5>
          </div>
        </div>
        <div class="card-body p-3">
          <div class="chart-container">
            {!! $chartFiliere->container() !!}
          </div>
        </div>
      </div>
    </div>

    <!-- Chart by Groupe -->
    <div class="col-12 col-xl-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-light border-0 py-3">
          <div class="d-flex align-items-center">
            <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
              <i class="bi bi-bar-chart text-success"></i>
            </div>
            <h5 class="mb-0 fw-bold text-success">Absences par groupe</h5>
          </div>
        </div>
        <div class="card-body p-3">
          <div class="chart-container">
            {!! $chartGroupe->container() !!}
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Table Section -->
  <div class="row">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                <i class="bi bi-table text-primary"></i>
              </div>
              <div>
                <h5 class="mb-0 fw-bold text-primary">Détails des absences</h5>
                <small class="text-muted">Liste complète des absences enregistrées</small>
              </div>
            </div>
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" onclick="toggleTableView()">
                <i class="bi bi-arrows-expand me-1"></i>Plein écran
              </button>
            </div>
          </div>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive" id="tableContainer">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-primary sticky-top">
                <tr>
                  <th class="px-3 py-3">
                    <i class="bi bi-person me-1"></i>Étudiant
                  </th>
                  <th class="px-3 py-3 d-none d-md-table-cell">
                    <i class="bi bi-mortarboard me-1"></i>Filière
                  </th>
                  <th class="px-3 py-3 d-none d-lg-table-cell">
                    <i class="bi bi-people me-1"></i>Groupe
                  </th>
                  <th class="px-3 py-3">
                    <i class="bi bi-book me-1"></i>Cours
                  </th>
                  <th class="px-3 py-3 d-none d-md-table-cell">
                    <i class="bi bi-calendar me-1"></i>Date
                  </th>
                  <th class="px-3 py-3 d-none d-lg-table-cell">
                    <i class="bi bi-clock me-1"></i>Heure
                  </th>
                  <th class="px-3 py-3 text-center">
                    <i class="bi bi-check-circle me-1"></i>État
                  </th>
                </tr>
              </thead>
              <tbody>
                @forelse($absencesDetaillees as $absence)
                <tr class="border-bottom">
                  <td class="px-3 py-3">
                    <div class="d-flex align-items-center">
                      <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                        {{ substr($absence->etudiant->user->prenom, 0, 1) }}{{ substr($absence->etudiant->user->name, 0, 1) }}
                      </div>
                      <div>
                        <div class="fw-bold">{{ $absence->etudiant->user->prenom }} {{ $absence->etudiant->user->name }}</div>
                        <div class="d-md-none">
                          <small class="text-muted">{{ $absence->etudiant->groupe->filiere->nom_filiere ?? '-' }}</small>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="px-3 py-3 d-none d-md-table-cell">
                    <span class="badge bg-info bg-opacity-10 text-info">
                      {{ $absence->etudiant->groupe->filiere->nom_filiere ?? '-' }}
                    </span>
                  </td>
                  <td class="px-3 py-3 d-none d-lg-table-cell">
                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                      {{ $absence->etudiant->groupe->nom_groupe ?? '-' }}
                    </span>
                  </td>
                  <td class="px-3 py-3">
                    <div class="fw-medium">{{ $absence->session->cours->nom ?? '-' }}</div>
                    <div class="d-md-none">
                      <small class="text-muted">
                        {{ $absence->session->date ? \Carbon\Carbon::parse($absence->session->date)->format('d/m/Y') : '-' }}
                      </small>
                    </div>
                  </td>
                  <td class="px-3 py-3 d-none d-md-table-cell">
                    <div class="fw-medium">
                      {{ $absence->session->date ? \Carbon\Carbon::parse($absence->session->date)->format('d/m/Y') : '-' }}
                    </div>
                  </td>
                  <td class="px-3 py-3 d-none d-lg-table-cell">
                    <small class="text-muted">
                      {{ $absence->session->heure_debut ?? '--:--' }} - {{ $absence->session->heure_fin ?? '--:--' }}
                    </small>
                  </td>
                  <td class="px-3 py-3 text-center">
                    @if($absence->etat == 0 && $absence->justification)
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                      <i class="bi bi-exclamation-triangle me-1"></i>Absent justifié
                    </span>
                    @elseif($absence->etat == 0)
                    <span class="badge bg-danger px-3 py-2 rounded-pill">
                      <i class="bi bi-x-circle me-1"></i>Absent
                    </span>
                    @else
                    <span class="badge bg-success px-3 py-2 rounded-pill">
                      <i class="bi bi-check-circle me-1"></i>Présent
                    </span>
                    @endif
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" class="text-center py-5">
                    <div class="text-muted">
                      <i class="bi bi-inbox display-4 mb-3 d-block"></i>
                      <h5>Aucune absence trouvée</h5>
                      <p class="mb-0">Aucune donnée ne correspond aux critères sélectionnés.</p>
                    </div>
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          @if($absencesDetaillees->hasPages())
          <div class="card-footer bg-light border-0">
            <div class="d-flex justify-content-center">
              {{ $absencesDetaillees->links() }}
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{!! $chartFiliere->script() !!}
{!! $chartGroupe->script() !!}
@endsection

@push('styles')
<style>
  /* Custom gradient */
  .bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  }

  /* Chart containers */
  .chart-container {
    position: relative;
    width: 100%;
    height: 350px;
    padding: 1rem;
  }

  .chart-container canvas {
    max-height: 100% !important;
    width: 100% !important;
  }

  /* Enhanced table styling */
  .table {
    font-size: 0.9rem;
  }

  .table thead th {
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
    background-color: rgba(13, 110, 253, 0.1) !important;
  }

  .table tbody tr {
    transition: all 0.2s ease;
  }

  .table tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
    transform: translateX(2px);
  }

  /* Avatar styling */
  .avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 0.8rem;
    font-weight: 600;
  }

  /* Badge enhancements */
  .badge {
    font-size: 0.8rem;
    font-weight: 500;
    padding: 0.5rem 0.75rem;
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

  /* Form enhancements */
  .form-control:focus,
  .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    transform: translateY(-1px);
    transition: all 0.3s ease;
  }

  /* Sticky header for table */
  .sticky-top {
    position: sticky;
    top: 0;
    z-index: 1020;
  }

  /* Loading animation for charts */
  .chart-container::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #0d6efd;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    z-index: 1;
  }

  .chart-container canvas {
    position: relative;
    z-index: 2;
  }

  @keyframes spin {
    0% {
      transform: translate(-50%, -50%) rotate(0deg);
    }

    100% {
      transform: translate(-50%, -50%) rotate(360deg);
    }
  }

  /* Enhanced pagination */
  .pagination {
    margin-bottom: 0;
  }

  .page-link {
    border-radius: 50px;
    margin: 0 2px;
    border: none;
    color: #0d6efd;
    transition: all 0.3s ease;
  }

  .page-link:hover {
    background-color: #e9ecef;
    color: #0056b3;
    transform: translateY(-1px);
  }

  .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
  }

  /* Fullscreen table */
  .table-fullscreen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 9999;
    background: white;
    overflow: auto;
  }

  .table-fullscreen .card {
    height: 100vh;
    border-radius: 0;
  }

  /* Responsive improvements */
  @media (max-width: 768px) {
    .chart-container {
      height: 300px;
      padding: 0.5rem;
    }

    .table {
      font-size: 0.8rem;
    }

    .avatar-sm {
      width: 32px;
      height: 32px;
      font-size: 0.7rem;
    }

    .badge {
      font-size: 0.7rem;
      padding: 0.25rem 0.5rem;
    }

    .card-body {
      padding: 1rem !important;
    }
  }

  @media (max-width: 576px) {
    .chart-container {
      height: 250px;
      padding: 0.25rem;
    }

    .table {
      font-size: 0.75rem;
    }

    .px-3 {
      padding-left: 0.75rem !important;
      padding-right: 0.75rem !important;
    }

    .py-3 {
      padding-top: 0.75rem !important;
      padding-bottom: 0.75rem !important;
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

    .table {
      font-size: 0.8rem;
    }

    .chart-container {
      height: 300px !important;
    }
  }

  /* Smooth scrolling */
  html {
    scroll-behavior: smooth;
  }

  /* Focus states for accessibility */
  .btn:focus,
  .form-control:focus,
  .form-select:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
  }

  /* Animation for cards entrance */
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

  @keyframes cardEntrance {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Quick stats cards hover effect */
  .card-body:hover .bg-opacity-10 {
    transform: scale(1.1);
    transition: transform 0.3s ease;
  }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Toggle fullscreen table view
    window.toggleTableView = function() {
      const tableContainer = document.getElementById('tableContainer');
      const card = tableContainer.closest('.card');
      const btn = document.querySelector('[onclick="toggleTableView()"]');

      if (card.classList.contains('table-fullscreen')) {
        card.classList.remove('table-fullscreen');
        btn.innerHTML = '<i class="bi bi-arrows-expand me-1"></i>Plein écran';
        document.body.style.overflow = 'auto';
      } else {
        card.classList.add('table-fullscreen');
        btn.innerHTML = '<i class="bi bi-arrows-collapse me-1"></i>Réduire';
        document.body.style.overflow = 'hidden';
      }
    };

    // Escape key to exit fullscreen
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        const fullscreenCard = document.querySelector('.table-fullscreen');
        if (fullscreenCard) {
          toggleTableView();
        }
      }
    });

    // Auto-refresh charts every 5 minutes
    setInterval(function() {
      if (typeof window.chartFiliere !== 'undefined') {
        window.chartFiliere.update();
      }
      if (typeof window.chartGroupe !== 'undefined') {
        window.chartGroupe.update();
      }
    }, 300000); // 5 minutes

    // Smooth scroll to table when filtering
    const form = document.querySelector('form[method="GET"]');
    if (form) {
      form.addEventListener('submit', function() {
        setTimeout(() => {
          document.querySelector('.table-responsive').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }, 100);
      });
    }

    // Enhanced print functionality
    window.addEventListener('beforeprint', function() {
      // Hide chart loading animations
      document.querySelectorAll('.chart-container::before').forEach(el => {
        el.style.display = 'none';
      });
    });

    // Tooltip for badges
    const badges = document.querySelectorAll('.badge');
    badges.forEach(badge => {
      badge.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.05)';
      });

      badge.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
      });
    });

    // Loading state for export button
    const exportBtn = document.querySelector('a[href*="export"]');
    if (exportBtn) {
      exportBtn.addEventListener('click', function() {
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Export...';
        this.classList.add('disabled');

        setTimeout(() => {
          this.innerHTML = originalText;
          this.classList.remove('disabled');
        }, 3000);
      });
    }

    // Animate quick stats on scroll
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const counter = entry.target.querySelector('h4');
          const target = parseInt(counter.textContent);
          let current = 0;
          const increment = target / 50;

          const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
              counter.textContent = target;
              clearInterval(timer);
            } else {
              counter.textContent = Math.floor(current);
            }
          }, 20);
        }
      });
    });

    document.querySelectorAll('.card-body').forEach(card => {
      if (card.querySelector('h4')) {
        observer.observe(card);
      }
    });
  });
</script>
@endpush