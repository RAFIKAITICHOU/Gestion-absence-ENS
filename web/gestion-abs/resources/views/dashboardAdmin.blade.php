@extends('layouts.adminMenu')
@section('breadcrumb', 'Tableau de bord')

@section('content')
<div class="container-fluid py-4">
  <!-- Header Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-lg bg-gradient-dashboard text-white rounded-4">
        <div class="card-body py-4 px-4">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h1 class="mb-2 fw-bold">
                Bienvenue, <span class="text-warning">{{ Auth::user()->prenom }} {{ Auth::user()->name }}</span> ! 👋
              </h1>
              <p class="mb-0 opacity-75 fs-5">Tableau de bord administrateur - Vue d'ensemble du système</p>
              <small class="opacity-50">Dernière connexion : {{ now()->format('d/m/Y à H:i') }}</small>
            </div>
            <div class="d-none d-lg-block">
              <div class="dashboard-icon">
                <i class="bi bi-speedometer2 display-3 opacity-25"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Stats Cards -->
  <div class="row g-4 mb-4">
    <!-- Étudiants Card -->
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-lg rounded-4 stat-card bg-gradient-primary text-white h-100">
        <a href="{{ route('gestion.utilisateurs', ['tab' => 'etudiants']) }}" class="text-decoration-none text-white">
          <div class="card-body p-4 text-center position-relative overflow-hidden">
            <div class="stat-icon mb-3">
              <i class="bi bi-people-fill fs-1"></i>
            </div>
            <h3 class="fw-bold mb-1 counter" data-target="{{ $nombreEtudiants }}">0</h3>
            <p class="mb-2 opacity-75">Étudiants</p>
            <div class="progress bg-white bg-opacity-25" style="height: 4px;">
              <div class="progress-bar bg-white" style="width: 85%"></div>
            </div>
            <div class="stat-bg-icon">
              <i class="bi bi-people-fill"></i>
            </div>
          </div>
        </a>
      </div>
    </div>

    <!-- Professeurs Card -->
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-lg rounded-4 stat-card bg-gradient-success text-white h-100">
        <a href="{{ route('gestion.utilisateurs', ['tab' => 'professeurs']) }}" class="text-decoration-none text-white">
          <div class="card-body p-4 text-center position-relative overflow-hidden">
            <div class="stat-icon mb-3">
              <i class="bi bi-mortarboard-fill fs-1"></i>
            </div>
            <h3 class="fw-bold mb-1 counter" data-target="{{ $nombreProfesseurs }}">0</h3>
            <p class="mb-2 opacity-75">Professeurs</p>
            <div class="progress bg-white bg-opacity-25" style="height: 4px;">
              <div class="progress-bar bg-white" style="width: 70%"></div>
            </div>
            <div class="stat-bg-icon">
              <i class="bi bi-mortarboard-fill"></i>
            </div>
          </div>
        </a>
      </div>
    </div>

    <!-- Absences Card -->
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-lg rounded-4 stat-card bg-gradient-danger text-white h-100">
        <div class="card-body p-4 text-center position-relative overflow-hidden">
          <div class="stat-icon mb-3">
            <i class="bi bi-exclamation-triangle-fill fs-1"></i>
          </div>
          <h3 class="fw-bold mb-1 counter" data-target="{{ $nombreAbsences }}">0</h3>
          <p class="mb-2 opacity-75">Absences</p>
          <div class="progress bg-white bg-opacity-25" style="height: 4px;">
            <div class="progress-bar bg-white" style="width: {{ $pourcentageAbsences ?? 0 }}%"></div>
          </div>
          <div class="stat-bg-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Taux de présence Card -->
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-lg rounded-4 stat-card bg-gradient-info text-white h-100">
        <div class="card-body p-4 text-center position-relative overflow-hidden">
          <div class="stat-icon mb-3">
            <i class="bi bi-graph-up-arrow fs-1"></i>
          </div>
          <h3 class="fw-bold mb-1">{{ 100 - ($pourcentageAbsences ?? 0) }}%</h3>
          <p class="mb-2 opacity-75">Taux de présence</p>
          <div class="progress bg-white bg-opacity-25" style="height: 4px;">
            <div class="progress-bar bg-white" style="width: {{ 100 - ($pourcentageAbsences ?? 0) }}%"></div>
          </div>
          <div class="stat-bg-icon">
            <i class="bi bi-graph-up-arrow"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts Section -->
  <div class="row g-4 mb-4">
    <!-- Absences par Filière -->
    <div class="col-12 col-lg-8">
      <div class="card border-0 shadow-lg rounded-4 h-100">
        <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4 py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
              <i class="bi bi-bar-chart me-2"></i>Absences par Filière
            </h5>
            <div class="dropdown">
              <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-three-dots"></i>
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"><i class="bi bi-download me-2"></i>Exporter</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Imprimer</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="card-body p-4">
          <div class="chart-container" style="height: 350px;">
            <canvas id="absenceFiliereChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Évolution mensuelle -->
    <div class="col-12 col-lg-4">
      <div class="card border-0 shadow-lg rounded-4 h-100">
        <div class="card-header bg-gradient-success text-white border-0 rounded-top-4 py-3">
          <h5 class="mb-0 fw-bold">
            <i class="bi bi-graph-up me-2"></i>Évolution Mensuelle
          </h5>
        </div>
        <div class="card-body p-4">
          <div class="chart-container" style="height: 350px;">
            <canvas id="evolutionChart"></canvas>
          </div>
        </div>
      </div>
    </div>

  </div>
  <!-- System Status -->
  <div class="row g-4">
    <div class="col-12">
      <div class="card border-0 shadow-lg rounded-4">
        <div class="card-header bg-gradient-dark text-white border-0 rounded-top-4 py-3">
          <h5 class="mb-0 fw-bold">
            <i class="bi bi-server me-2"></i>État du Système
          </h5>
        </div>
        <div class="card-body p-4">
          <div class="row g-4">
            <div class="col-6 col-md-3">
              <div class="text-center">
                <div class="status-indicator bg-success mb-2"></div>
                <div class="fw-semibold">Base de données</div>
                <small class="text-success">Opérationnelle</small>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="text-center">
                <div class="status-indicator bg-success mb-2"></div>
                <div class="fw-semibold">Serveur Web</div>
                <small class="text-success">En ligne</small>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="text-center">
                <div class="status-indicator bg-warning mb-2"></div>
                <div class="fw-semibold">Stockage</div>
                <small class="text-warning">75% utilisé</small>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="text-center">
                <div class="status-indicator bg-success mb-2"></div>
                <div class="fw-semibold">Sauvegardes</div>
                <small class="text-success">À jour</small>
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
  /* Variables CSS */
  :root {
    --dashboard-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
    --info-gradient: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
    --warning-gradient: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);
    --danger-gradient: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);
    --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    --shadow-light: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    --shadow-medium: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    --shadow-heavy: 0 1rem 3rem rgba(0, 0, 0, 0.175);
  }

  /* Background gradients */
  .bg-gradient-dashboard {
    background: var(--dashboard-gradient) !important;
  }

  .bg-gradient-primary {
    background: var(--primary-gradient) !important;
  }

  .bg-gradient-success {
    background: var(--success-gradient) !important;
  }

  .bg-gradient-info {
    background: var(--info-gradient) !important;
  }

  .bg-gradient-warning {
    background: var(--warning-gradient) !important;
  }

  .bg-gradient-danger {
    background: var(--danger-gradient) !important;
  }

  .bg-gradient-dark {
    background: var(--dark-gradient) !important;
  }

  /* Dashboard header */
  .dashboard-icon {
    animation: float 3s ease-in-out infinite;
  }

  @keyframes float {

    0%,
    100% {
      transform: translateY(0px);
    }

    50% {
      transform: translateY(-10px);
    }
  }

  /* Stat cards */
  .stat-card {
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }

  .stat-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: var(--shadow-heavy);
  }

  .stat-card .stat-icon {
    position: relative;
    z-index: 2;
  }

  .stat-card .stat-bg-icon {
    position: absolute;
    top: -20px;
    right: -20px;
    font-size: 8rem;
    opacity: 0.1;
    z-index: 1;
  }

  /* Counter animation */
  .counter {
    font-size: 2.5rem;
    font-weight: 700;
  }

  /* Charts */
  .chart-container {
    position: relative;
    width: 100%;
  }

  /* Activity list */
  .activity-list {
    max-height: 400px;
    overflow-y: auto;
  }

  .activity-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
  }

  .activity-item {
    transition: background-color 0.2s ease;
  }

  .activity-item:hover {
    background-color: #f8f9fa;
  }

  /* Status indicators */
  .status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin: 0 auto;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0% {
      box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
    }

    70% {
      box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
    }

    100% {
      box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
    }
  }

  .status-indicator.bg-success {
    animation-name: pulse-success;
  }

  .status-indicator.bg-warning {
    animation-name: pulse-warning;
  }

  @keyframes pulse-success {
    0% {
      box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
    }

    70% {
      box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
    }

    100% {
      box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
    }
  }

  @keyframes pulse-warning {
    0% {
      box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
    }

    70% {
      box-shadow: 0 0 0 10px rgba(255, 193, 7, 0);
    }

    100% {
      box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
    }
  }

  /* Cards */
  .card {
    transition: all 0.3s ease;
    border: none !important;
  }

  .card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-heavy) !important;
  }

  /* Buttons */
  .btn {
    transition: all 0.3s ease;
    font-weight: 500;
  }

  .btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
  }

  /* Progress bars */
  .progress {
    border-radius: 10px;
    overflow: hidden;
  }

  .progress-bar {
    transition: width 1s ease-in-out;
    border-radius: 10px;
  }

  /* Responsive Design */
  @media (max-width: 1200px) {
    .stat-card .stat-bg-icon {
      font-size: 6rem;
    }

    .counter {
      font-size: 2rem;
    }
  }

  @media (max-width: 992px) {
    .dashboard-icon {
      display: none !important;
    }

    .chart-container {
      height: 300px !important;
    }

    .activity-list {
      max-height: 300px;
    }
  }

  @media (max-width: 768px) {
    .container-fluid {
      padding-left: 1rem;
      padding-right: 1rem;
    }

    .card-body {
      padding: 1.5rem !important;
    }

    .counter {
      font-size: 1.8rem;
    }

    .stat-card .stat-bg-icon {
      font-size: 4rem;
      top: -10px;
      right: -10px;
    }

    .activity-icon {
      width: 35px;
      height: 35px;
      font-size: 0.9rem;
    }
  }

  @media (max-width: 576px) {
    .stat-card .card-body {
      padding: 1rem !important;
    }

    .counter {
      font-size: 1.5rem;
    }

    .chart-container {
      height: 250px !important;
    }

    .btn {
      font-size: 0.8rem;
      padding: 0.5rem !important;
    }

    .btn i {
      font-size: 1.2rem !important;
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
  .stat-card {
    animation: fadeInUp 0.6s ease-out;
  }

  .stat-card:nth-child(1) {
    animation-delay: 0.1s;
  }

  .stat-card:nth-child(2) {
    animation-delay: 0.2s;
  }

  .stat-card:nth-child(3) {
    animation-delay: 0.3s;
  }

  .stat-card:nth-child(4) {
    animation-delay: 0.4s;
  }

  /* Scrollbar personnalisée */
  .activity-list::-webkit-scrollbar {
    width: 6px;
  }

  .activity-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
  }

  .activity-list::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
  }

  .activity-list::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
  }

  /* Print styles */
  @media print {

    .btn,
    .dropdown {
      display: none !important;
    }

    .card {
      border: 1px solid #dee2e6 !important;
      box-shadow: none !important;
    }

    .stat-card {
      break-inside: avoid;
    }
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // 🔁 Rendu dynamique du graphique "Évolution Mensuelle"
    fetch('/evolution-mensuelle')
      .then(response => response.json())
      .then(data => {
        const monthLabels = data.map(item =>
          new Date(2025, item.month - 1).toLocaleString('fr-FR', {
            month: 'short'
          })
        );
        const counts = data.map(item => item.total);

        const ctx2 = document.getElementById('evolutionChart').getContext('2d');
        new Chart(ctx2, {
          type: 'line',
          data: {
            labels: monthLabels,
            datasets: [{
              label: 'Absences mensuelles',
              data: counts,
              borderColor: 'rgba(86, 171, 47, 1)',
              backgroundColor: 'rgba(86, 171, 47, 0.1)',
              borderWidth: 3,
              fill: true,
              tension: 0.4,
              pointBackgroundColor: 'rgba(86, 171, 47, 1)',
              pointBorderColor: '#fff',
              pointBorderWidth: 2,
              pointRadius: 6,
              pointHoverRadius: 8,
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false
              },
              tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: 'white',
                bodyColor: 'white',
                borderColor: 'rgba(255, 255, 255, 0.1)',
                borderWidth: 1,
                cornerRadius: 8,
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                grid: {
                  color: 'rgba(0, 0, 0, 0.1)',
                },
                ticks: {
                  color: '#6c757d',
                  font: {
                    size: 12
                  }
                }
              },
              x: {
                grid: {
                  display: false
                },
                ticks: {
                  color: '#6c757d',
                  font: {
                    size: 12
                  }
                }
              }
            },
            animation: {
              duration: 2000,
              easing: 'easeInOutQuart'
            }
          }
        });
      })
      .catch(error => {
        console.error('Erreur lors du chargement des données d’évolution mensuelle :', error);
      });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Animation des compteurs
    const counters = document.querySelectorAll('.counter[data-target]');
    counters.forEach(counter => {
      const target = parseInt(counter.getAttribute('data-target'));
      const duration = 2000; // 2 secondes
      const step = target / (duration / 16); // 60 FPS
      let current = 0;

      const timer = setInterval(() => {
        current += step;
        if (current >= target) {
          current = target;
          clearInterval(timer);
        }
        counter.textContent = Math.floor(current);
      }, 16);
    });

    // Graphique des absences par filière
    const absenceFiliereData = @json($absencesParFiliere);
    const filiereLabels = Object.keys(absenceFiliereData);
    const filiereCounts = Object.values(absenceFiliereData);

    const ctx1 = document.getElementById('absenceFiliereChart').getContext('2d');
    new Chart(ctx1, {
      type: 'bar',
      data: {
        labels: filiereLabels,
        datasets: [{
          label: "Nombre d'absences",
          data: filiereCounts,
          backgroundColor: [
            'rgba(102, 126, 234, 0.8)',
            'rgba(86, 171, 47, 0.8)',
            'rgba(116, 185, 255, 0.8)',
            'rgba(253, 203, 110, 0.8)',
            'rgba(253, 121, 168, 0.8)'
          ],
          borderColor: [
            'rgba(102, 126, 234, 1)',
            'rgba(86, 171, 47, 1)',
            'rgba(116, 185, 255, 1)',
            'rgba(253, 203, 110, 1)',
            'rgba(253, 121, 168, 1)'
          ],
          borderWidth: 2,
          borderRadius: 8,
          borderSkipped: false,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleColor: 'white',
            bodyColor: 'white',
            borderColor: 'rgba(255, 255, 255, 0.1)',
            borderWidth: 1,
            cornerRadius: 8,
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0, 0, 0, 0.1)',
            },
            ticks: {
              color: '#6c757d',
              font: {
                size: 12
              }
            }
          },
          x: {
            grid: {
              display: false
            },
            ticks: {
              color: '#6c757d',
              font: {
                size: 12
              }
            }
          }
        },
        animation: {
          duration: 2000,
          easing: 'easeInOutQuart'
        }
      }
    });

   

    // Animation des cartes au scroll
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, observerOptions);

    document.querySelectorAll('.card').forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      observer.observe(card);
    });

    // Mise à jour en temps réel des indicateurs de statut
    setInterval(() => {
      const indicators = document.querySelectorAll('.status-indicator');
      indicators.forEach(indicator => {
        // Simulation de changement d'état
        if (Math.random() > 0.95) {
          indicator.style.animationDuration = '0.5s';
          setTimeout(() => {
            indicator.style.animationDuration = '2s';
          }, 500);
        }
      });
    }, 5000);
  });
</script>
@endpush