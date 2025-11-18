@extends('layouts.' . $menu)

@section('title', $title ?? 'Dashboard Professeur')
@section('breadcrumb', 'Tableau de bord')

@section('content')
<div class="container-fluid py-3">
  {{-- En-tête --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="bg-primary-gradient text-white p-4 rounded-4 shadow-lg card-animated" data-delay="0.1">
        <h2 class="fw-bold mb-1"><i class="fas fa-user-tie me-2"></i> Bienvenue, <b>Pr.  {{ Auth::user()->prenom }} {{ Auth::user()->name }}</b> 👨‍🏫</h2>
        <p class="opacity-75 mb-0">Tableau de bord — {{ \Carbon\Carbon::now('Africa/Casablanca')->translatedFormat('l d F Y') }}</p>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden card-animated" data-delay="0.2">
        <div class="card-header bg-gradient-success text-white border-0 py-4">
          <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-check me-2"></i> Cours prévus aujourd'hui<span class="badge bg-light text-success ms-2 rounded-pill px-3 py-2">
              {{ \Carbon\Carbon::now('Africa/Casablanca')->format('d/m/Y') }}</span></h5>
        </div>
        <div class="card-body p-4">
          @if ($seancesDuJour->count() > 0)
          <div id="seance-container">
            @foreach ($seancesDuJour as $index => $seanceDuJour)
            @php
            $totalEtudiants = $seanceDuJour->groupe->etudiants->count();
            $presences = $seanceDuJour->presences->keyBy('id_etudiant');
            $totalPresents = $seanceDuJour->groupe->etudiants->filter(fn($e) => ($presences[$e->id]->etat ?? 1) === 1)->count();
            $totalAbsents = $seanceDuJour->groupe->etudiants->filter(fn($e) => ($presences[$e->id]->etat ?? 1) === 0)->count();
            $tauxPresence = $totalEtudiants > 0 ? round(($totalPresents / $totalEtudiants) * 100, 1) : 0;
            @endphp
            <div class="seance-item" style="{{ $index === 0 ? '' : 'display:none;' }}">
              {{-- Infos du cours --}}
              <div class="row mb-4 g-3">
                <div class="col-md-6">
                  <div class="info-card bg-light rounded-3 p-3 d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3 flex-shrink-0">
                      <i class="fas fa-book text-primary fs-5"></i>
                    </div>
                    <div>
                      <small class="text-muted d-block">Cours</small>
                      <div class="fw-bold text-dark">{{ $seanceDuJour->cours->nom }}</div>
                    </div>
                  </div>
                  <div class="info-card bg-light rounded-3 p-3 d-flex align-items-center mb-3">
                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3 flex-shrink-0">
                      <i class="fas fa-sitemap text-info fs-5"></i>
                    </div>
                    <div>
                      <small class="text-muted d-block">Filière</small>
                      <div class="fw-bold text-dark">{{ $seanceDuJour->groupe->filiere->nom_filiere ?? '-' }}</div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="info-card bg-light rounded-3 p-3 d-flex align-items-center mb-3">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3 flex-shrink-0">
                      <i class="fas fa-users text-warning fs-5"></i>
                    </div>
                    <div>
                      <small class="text-muted d-block">Groupe</small>
                      <div class="fw-bold text-dark">{{ $seanceDuJour->groupe->nom_groupe }}</div>
                    </div>
                  </div>
                  <div class="info-card bg-light rounded-3 p-3 d-flex align-items-center mb-3">
                    <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-3 flex-shrink-0">
                      <i class="fas fa-clock text-secondary fs-5"></i>
                    </div>
                    <div>
                      <small class="text-muted d-block">Horaire</small>
                      <div class="fw-bold text-dark">{{ $seanceDuJour->heure_debut }} - {{ $seanceDuJour->heure_fin }}</div>
                    </div>
                  </div>
                </div>
              </div>
              {{-- Statistiques --}}
              <div class="row text-center mb-4 g-3">
                <div class="col-4">
                  <div class="stat-item p-3 rounded-3 bg-primary bg-opacity-10">
                    <h5 class="mb-1 text-primary fw-bold">{{ $totalEtudiants }}</h5>
                    <small class="text-muted">Inscrits</small>
                  </div>
                </div>
                <div class="col-4">
                  <div class="stat-item p-3 rounded-3 bg-success bg-opacity-10">
                    <h5 class="mb-1 text-success fw-bold">{{ $totalPresents }}</h5>
                    <small class="text-muted">Présents</small>
                  </div>
                </div>
                <div class="col-4">
                  <div class="stat-item p-3 rounded-3 bg-danger bg-opacity-10">
                    <h5 class="mb-1 text-danger fw-bold">{{ $totalAbsents }}</h5>
                    <small class="text-muted">Absents</small>
                  </div>
                </div>
              </div>
              {{-- Analyse AI_Detect --}}
              <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden card-animated" data-delay="0.5">
                <div class="card-header bg-gradient-info text-white border-0 py-4">
                  <h5 class="mb-0 fw-bold"><i class="fas fa-video me-2"></i> Détectés par caméra</h5>
                  <small class="opacity-75">Analyse de la détection par IA</small>
                </div>
                <div class="card-body p-4">
                  @if (!is_null($seanceDuJour->ai_detect))
                  <div class="mb-3">
                    <span class="small fw-bold text-dark">Nombre détecté par caméra :</span>
                    <span class="fw-bold text-primary fs-5 ms-2">{{ $seanceDuJour->ai_detect }}</span>
                  </div>
                  @if ($seanceDuJour->ai_detect > $totalEtudiants)
                  <div class="alert alert-danger p-3 rounded-3 small mb-3">
                    <i class="fas fa-times-circle me-2"></i><strong>Anomalie détectée :</strong> Le nombre détecté par caméra (<strong>{{ $seanceDuJour->ai_detect }}</strong>)
                    est supérieur au total des étudiants inscrits (<strong>{{ $totalEtudiants }}</strong>).
                  </div>
                  @elseif ($seanceDuJour->ai_detect > $totalPresents)
                  <div class="alert alert-warning p-3 rounded-3 small mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i><strong>Alerte :</strong> Le nombre détecté par caméra (<strong>{{ $seanceDuJour->ai_detect }}</strong>)
                    est supérieur aux présences déclarées (<strong>{{ $totalPresents }}</strong>), mais reste cohérent.
                  </div>
                  @elseif ($seanceDuJour->ai_detect < $totalPresents)
                    <div class="alert alert-danger p-3 rounded-3 small mb-3"> 
                    <i class="fas fa-exclamation-triangle me-2"></i><strong>Alerte :</strong> Moins d’étudiants détectés par caméra (<strong>{{ $seanceDuJour->ai_detect }}</strong>)
                    que de présences déclarées (<strong>{{ $totalPresents }}</strong>).
                </div>
                @elseif ($seanceDuJour->ai_detect == $totalPresents)
                <div class="alert alert-success p-3 rounded-3 small mb-3">
                  <i class="fas fa-check-circle me-2"></i> Le comptage caméra est parfaitement cohérent avec les présences déclarées.
                </div>
                @endif
                @else
                <div class="alert alert-info p-3 rounded-3 small mb-0">
                  <i class="fas fa-info-circle me-2"></i> Aucune donnée de détection par caméra disponible pour cette séance.
                </div>
                @endif
              </div>
            </div>
            {{-- Progression --}}
            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-2"><span class="small fw-bold text-dark">Taux de présence</span><span class="small text-muted">{{ $totalPresents }}/{{ $totalEtudiants }}</span></div>
              <div class="progress" style="height: 10px; border-radius: 5px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $tauxPresence }}%;" aria-valuenow="{{ $tauxPresence }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            {{-- Bouton gérer --}}
            <div class="text-center mb-4">
              <a href="{{ url('/presences/gerer/' . $seanceDuJour->id) }}" class="btn btn-success btn-lg px-5 py-3 rounded-pill shadow-lg">
                <i class="fas fa-edit me-2"></i> Gérer la séance
              </a>
            </div>
          </div>
          @endforeach
        </div>
        {{-- Navigation --}}
        <div class="text-center mt-4">
          <button id="prevSeance" class="btn btn-outline-secondary rounded-pill px-4 py-2 me-2"><i class="fas fa-arrow-left me-1"></i> Précédent</button>
          <span id="seance-indicator" class="mx-2 text-muted small d-inline-block fw-bold"></span>
          <button id="nextSeance" class="btn btn-outline-primary rounded-pill px-4 py-2"><i class="fas fa-arrow-right me-1"></i> Suivant</button>
        </div>
        @else
        <div class="text-center py-5">
          <i class="fas fa-calendar-times display-1 text-muted opacity-50 mb-4"></i>
          <div class="alert alert-info rounded-pill d-inline-block px-4 py-3 shadow-sm">
            <i class="fas fa-info-circle me-2"></i> Aucun cours prévu aujourd’hui.
          </div>
          <p class="text-muted mt-3">Profitez de cette journée pour préparer vos prochains cours.</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

{{-- Statistiques --}}
<div class="row mt-5">
  <div class="col-12">
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden card-animated" data-delay="0.7">
      <div class="alert alert-danger border-0 rounded-0 mb-0 py-3">
        <i class="fas fa-exclamation-triangle me-2 text-danger"></i><strong class="text-danger">Remarque :</strong> plus de 20% d'absences = exclusion de l'examen final.
      </div>
      <div class="card-header bg-gradient-primary text-white border-0 py-4">
        <h5 class="mb-0 fw-bold"><i class="fas fa-chart-bar me-2"></i> Statistiques des absences par module</h5>
        <small class="opacity-75">Absences justifiées et non justifiées</small>
      </div>
      <div class="card-body p-4">
        <div class="chart-container bg-light rounded-3 p-3 shadow-sm" style="position: relative; height:400px;">
          {!! $chart->container() !!}
        </div>
      </div>
    </div>
  </div>
</div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<style>
  /* Custom Gradients */
  .bg-primary-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    /* Standardized primary gradient */
  }

  .bg-gradient-primary {
    /* Keeping original for this dashboard if it's distinct */
    background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
  }

  .bg-gradient-success {
    background: linear-gradient(135deg, #198754 0%, #146c43 100%);
  }

  .bg-gradient-info {
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
  }

  .bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #d39e00 100%);
  }

  .bg-gradient-danger {
    background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
  }

  /* Card and Button Enhancements */
  .card {
    border-radius: 1rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    transform: translateY(-3px);
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15) !important;
  }

  .btn {
    border-radius: 2rem;
    /* Standardized to rounded-pill */
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  }

  .btn-lg {
    padding: 1rem 2.5rem;
    font-size: 1.1rem;
  }

  /* Info Cards and Stat Items */
  .info-card,
  .stat-item {
    background-color: #f8f9fa;
    /* Light background */
    border: 1px solid #e9ecef;
    border-radius: 0.75rem;
    padding: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
  }

  .info-card:hover,
  .stat-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
  }

  .info-card .fas {
    /* Changed .bi to .fas */
    font-size: 1.5rem;
  }

  .stat-item h5 {
    font-size: 1.8rem;
    margin-bottom: 0.25rem;
  }

  .stat-item small {
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  /* Progress Bar */
  .progress {
    height: 10px;
    border-radius: 5px;
    background-color: #e9ecef;
  }

  .progress-bar {
    transition: width 0.6s ease;
    border-radius: 5px;
  }

  /* Alerts */
  .alert {
    border-radius: 1rem;
    /* Standardized border-radius */
    font-size: 0.95rem;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: flex-start;
    animation: slideInDown 0.5s ease-out;
    /* Added animation back for alerts */
  }

  .alert .fas {
    /* Changed .bi to .fas */
    font-size: 1.3rem;
    margin-right: 0.75rem;
    flex-shrink: 0;
  }

  .alert-info {
    background-color: #e0f7fa;
    color: #006064;
    border-left: 5px solid #00bcd4;
  }

  .alert-warning {
    background-color: #fffde7;
    color: #ff6f00;
    border-left: 5px solid #ffc107;
  }

  .alert-danger {
    background-color: #ffebee;
    color: #c62828;
    border-left: 5px solid #dc3545;
  }

  .alert-success {
    background-color: #e8f5e9;
    color: #2e7d32;
    border-left: 5px solid #198754;
  }

  @keyframes slideInDown {

    /* Added animation keyframes for alerts */
    from {
      transform: translateY(-100%);
      opacity: 0;
    }

    to {
      transform: translateY(0);
      opacity: 1;
    }
  }

  /* Chart Container */
  .chart-container {
    min-height: 400px;
    background-color: #fff;
    border: 1px solid #e9ecef;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
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

  /* Responsive Adjustments */
  @media (max-width: 768px) {
    .container-fluid {
      padding-left: 1rem;
      padding-right: 1rem;
    }

    .card-body {
      padding: 1.5rem !important;
    }

    .btn-lg {
      padding: 0.8rem 2rem;
      font-size: 1rem;
    }

    .info-card,
    .stat-item {
      padding: 0.8rem;
    }

    .stat-item h5 {
      font-size: 1.5rem;
    }

    .stat-item small {
      font-size: 0.8rem;
    }

    .alert {
      font-size: 0.85rem;
      padding: 0.8rem 1rem;
    }

    .alert .fas {
      /* Changed .bi to .fas */
      font-size: 1.1rem;
      margin-right: 0.5rem;
    }

    .chart-container {
      min-height: 300px;
      padding: 1rem;
    }
  }

  @media (max-width: 576px) {
    .card-body {
      padding: 1rem !important;
    }

    .btn-lg {
      padding: 0.7rem 1.5rem;
      font-size: 0.9rem;
    }

    .info-card .fas {
      /* Changed .bi to .fas */
      font-size: 1.2rem;
    }

    .stat-item h5 {
      font-size: 1.3rem;
    }

    .stat-item small {
      font-size: 0.7rem;
    }

    .alert {
      font-size: 0.8rem;
      padding: 0.6rem 0.8rem;
    }

    .alert .fas {
      /* Changed .bi to .fas */
      font-size: 1rem;
    }

    .chart-container {
      min-height: 250px;
      padding: 0.8rem;
    }
  }
</style>
@endpush

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{!! $chart->script() !!}

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const seances = document.querySelectorAll('.seance-item');
    const indicator = document.getElementById('seance-indicator');
    let index = 0;

    function updateSeanceDisplay() {
      seances.forEach((seance, i) => {
        seance.style.display = (i === index) ? 'block' : 'none';
      });
      if (indicator) {
        indicator.textContent = `Séance ${index + 1} sur ${seances.length}`;
      }
      document.getElementById('prevSeance').disabled = (index === 0);
      document.getElementById('nextSeance').disabled = (index === seances.length - 1);
    }

    document.getElementById('prevSeance')?.addEventListener('click', function() {
      if (index > 0) {
        index--;
        updateSeanceDisplay();
      }
    });

    document.getElementById('nextSeance')?.addEventListener('click', function() {
      if (index < seances.length - 1) {
        index++;
        updateSeanceDisplay();
      }
    });

    updateSeanceDisplay(); // Initialisation

    // Smooth card entrance animation with delays
    const cards = document.querySelectorAll('.card-animated');
    cards.forEach((card) => {
      const delay = parseFloat(card.dataset.delay || '0');
      card.style.animationDelay = `${delay}s`;
    });

    // Auto-dismiss alerts after 5 seconds
  //   const alerts = document.querySelectorAll('.alert');
  //   alerts.forEach(alert => {
  //     setTimeout(() => {
  //       if (alert && alert.parentNode) {
  //         alert.style.transition = 'all 0.5s ease';
  //         alert.style.opacity = '0';
  //         alert.style.transform = 'translateY(-20px)';
  //         setTimeout(() => {
  //           if (alert.parentNode) {
  //             alert.remove();
  //           }
  //         }, 500);
  //       }
  //     }, 5000);
  //   });
   });
</script>
@endsection