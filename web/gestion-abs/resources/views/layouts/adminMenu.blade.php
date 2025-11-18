<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>absENS - Espace Admin</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="icon" type="image/png" href="{{ asset('/assets/images/icon.png') }}" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Bootstrap JS (via CDN) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/b719ed3eb8.js" crossorigin="anonymous"></script>

  <style>
    /* Variables */
    :root {
      --sidebar-width: 250px;
      --primary-color: #0d6efd;
      --sidebar-bg: #ffffff;
      --nav-bg: #f8f9fa;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background-color: #f5f5f5;
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: var(--sidebar-width);
      height: 100vh;
      background: var(--sidebar-bg);
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
      z-index: 1000;
      transition: all 0.3s ease;
      overflow-y: auto;
    }

    .sidebar-header {
      padding: 2rem 1.5rem;
      text-align: center;
      border-bottom: 1px solid #dee2e6;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      min-height: 120px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .sidebar-logo {
      max-height: 80px;
      max-width: 180px;
      width: auto;
      height: auto;
      object-fit: contain;
      transition: transform 0.2s ease;
    }

    .sidebar-logo:hover {
      transform: scale(1.05);
    }

    .logo-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.5rem;
    }

    .logo-text {
      font-size: 0.85rem;
      color: #6c757d;
      font-weight: 500;
      text-align: center;
      margin-top: 0.5rem;
    }

    .nav-menu {
      list-style: none;
      padding: 1.5rem 0;
      margin: 0;
    }

    .nav-item {
      margin: 0;
    }

    .nav-link {
      display: flex;
      align-items: center;
      padding: 0.875rem 1.5rem;
      color: #495057;
      text-decoration: none;
      transition: all 0.2s ease;
      border-left: 3px solid transparent;
    }

    .nav-link:hover {
      background-color: #f8f9fa;
      color: var(--primary-color);
      border-left-color: var(--primary-color);
    }

    .nav-item.active .nav-link {
      background-color: #e7f3ff;
      color: var(--primary-color);
      border-left-color: var(--primary-color);
      font-weight: 500;
    }

    .nav-link i {
      margin-right: 0.75rem;
      width: 20px;
      text-align: center;
      font-size: 1.1rem;
    }

    /* Logout section */
    .sidebar-footer {
      position: absolute;
      bottom: 1rem;
      left: 1rem;
      right: 1rem;
    }

    /* Page Container */
    .page-container {
      margin-left: var(--sidebar-width);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Top Navbar */
    .navbar {
      background: var(--nav-bg) !important;
      border-bottom: 1px solid #dee2e6;
      padding: 0.75rem 1.5rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .navbar .dropdown-toggle::after {
      margin-left: 0.5rem;
    }

    /* Main Content */
    .main-content {
      flex: 1;
      padding: 0;
    }

    .container-fluid {
      padding: 1.5rem;
    }

    /* Breadcrumb */
    .breadcrumb-wrapper {
      margin-bottom: 1.5rem;
    }

    .breadcrumb {
      background: white;
      padding: 0.75rem 1rem;
      border-radius: 0.5rem;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      margin-bottom: 0;
    }

    .breadcrumb-item a {
      color: var(--primary-color);
      text-decoration: none;
    }

    .breadcrumb-item.active {
      color: #6c757d;
    }

    /* Footer */
    .content-footer {
      background: white;
      border-top: 1px solid #dee2e6;
      padding: 1rem 0;
      margin-top: auto;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
      .sidebar {
        left: -250px;
      }

      .sidebar.open {
        left: 0;
      }

      .page-container {
        margin-left: 0;
      }

      .mobile-toggle {
        display: block !important;
      }

      .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
        display: none;
      }

      .sidebar-overlay.show {
        display: block;
      }

      .breadcrumb-wrapper .col-12 {
        text-align: left !important;
      }

      .sidebar-header {
        padding: 1.5rem 1rem;
        min-height: 100px;
      }

      .sidebar-logo {
        max-height: 60px;
        max-width: 150px;
      }

      .logo-text {
        font-size: 0.75rem;
      }
    }

    .mobile-toggle {
      display: none;
      position: fixed;
      top: 15px;
      left: 15px;
      z-index: 1100;
    }

    /* Dropdown improvements */
    .dropdown-menu {
      border: 1px solid #dee2e6;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
      border-radius: 0.5rem;
    }

    .dropdown-item {
      padding: 0.5rem 1rem;
    }

    .dropdown-item:hover {
      background-color: #f8f9fa;
    }

    /* Scrollbar */
    .sidebar::-webkit-scrollbar {
      width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    .sidebar::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 3px;
    }

    /* Logo area enhancements */
    .sidebar-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--primary-color), #0056b3);
    }
  </style>

  @livewireStyles
  @stack('styles')
</head>

<body>
  <!-- Mobile Overlay -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <!-- Mobile Toggle Button -->
  <button class="btn btn-primary mobile-toggle" id="toggleSidebar">
    <i class="bi bi-list"></i>
  </button>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <div class="logo-container">
        <a href="{{ route('dashboardadmin') }}">
          <img src="{{ asset('images/logo.png') }}" class="sidebar-logo" alt="Logo gestion ABS">
        </a>
        <div class="logo-text">
          Gestion des Absences<br>
          <small class="text-muted">absENS</small>
        </div>
      </div>
    </div>

    <ul class="nav-menu">
      <li class="nav-item {{ request()->routeIs('dashboardadmin') ? 'active' : '' }}">
        <a href="{{ route('dashboardadmin') }}" class="nav-link">
          <i class="bi bi-house"></i> Dashboard
        </a>
      </li>
      <li class="nav-item {{ request()->routeIs('gestion.utilisateurs') || request()->routeIs('etudiants.*') || request()->routeIs('admins.*') || request()->routeIs('professeurs.*') ? 'active' : '' }}">
        <a href="{{ route('gestion.utilisateurs') }}" class="nav-link">
          <i class="bi bi-people"></i> Gestion des utilisateurs
        </a>
      </li>

      <li class="nav-item {{ (request()->routeIs('dashboardadmin.GestionEDT') || request()->routeIs('cours-sessions.*')) ? 'active' : '' }}">
        <a href="{{ route('dashboardadmin.GestionEDT') }}" class="nav-link">
          <i class="bi bi-calendar-week"></i> Gestion EDT
        </a>
      </li>

      <li class="nav-item {{ request()->routeIs('statistiques') ? 'active' : '' }}">
        <a href="{{ route('statistiques') }}" class="nav-link">
          <i class="bi bi-bar-chart"></i> Statistiques
        </a>
      </li>

      <li class="nav-item {{ request()->routeIs('liste.presences') ? 'active' : '' }}">
        <a href="{{ route('liste.presences') }}" class="nav-link">
          <i class="bi bi-clipboard-check"></i> Listes des présences
        </a>
      </li>
      <li class="nav-item {{ request()->routeIs('dashboardadmin.configuration') || request()->routeIs('jours_inactifs.*') || request()->routeIs('salles.*') || request()->routeIs('filieres.*') || request()->routeIs('filieres.*') || request()->routeIs('groupes.*') ? 'active' : '' }}">
        <a href="{{ route('dashboardadmin.configuration') }}" class="nav-link">
          <i class="bi bi-gear"></i> Configuration
        </a>
      </li>

      <li class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
        <a href="{{ route('profile.edit') }}" class="nav-link">
          <i class="bi bi-person-circle"></i> Mon profil
        </a>
      </li>

      <li class="nav-item {{ request()->routeIs('annonces.*') ? 'active' : '' }}">
        <a href="{{ route('annonces.index') }}" class="nav-link">
          <i class="bi bi-megaphone"></i> Annonces
        </a>
      </li>
    </ul>

    <div class="sidebar-footer">
      <a href="{{ route('logout') }}" class="btn btn-outline-danger w-100">
        <i class="bi bi-box-arrow-right me-2"></i>Se déconnecter
      </a>
    </div>
  </div>

  <!-- Page Container -->
  <div class="page-container">
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
      <div class="container-fluid justify-content-end">
        <ul class="navbar-nav align-items-center">
          <!-- User Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-dark d-flex align-items-center"
              href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-2"></i>
              {{ Auth::user()->prenom }} {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                  <i class="bi bi-gear me-2"></i> Mon profil
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                  <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
      <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="breadcrumb-wrapper row mb-3">
          <div class="col-12 col-lg-6 text-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="{{ route('dashboardadmin') }}">Accueil</a>
              </li>
              <li class="breadcrumb-item active">
                @yield('breadcrumb')
              </li>
            </ol>
          </div>
        </div>

        @yield('content')
      </div>
    </div>

    <!-- Footer -->
    <footer class="content-footer">
      <div class="footer text-center py-3">
        <span>&copy; {{ date('Y') }} <b class="text-dark">Gestion des absences - AIT ICHOU</b>. Tous droits réservés</span>
      </div>
    </footer>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const toggleBtn = document.getElementById("toggleSidebar");
      const sidebar = document.getElementById("sidebar");
      const overlay = document.getElementById("sidebarOverlay");

      // Toggle sidebar
      toggleBtn?.addEventListener("click", function() {
        sidebar.classList.toggle("open");
        overlay.classList.toggle("show");
      });

      // Close sidebar when clicking overlay
      overlay?.addEventListener("click", function() {
        sidebar.classList.remove("open");
        overlay.classList.remove("show");
      });

      // Close sidebar on window resize
      window.addEventListener("resize", function() {
        if (window.innerWidth > 768) {
          sidebar.classList.remove("open");
          overlay.classList.remove("show");
        }
      });
    });
  </script>

  @livewireScripts
  @yield('scripts')
  @stack('scripts')
</body>

</html>