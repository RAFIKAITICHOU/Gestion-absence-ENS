<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>absENS - Espace Etudiant</title>
  <link rel="icon" type="image/png" href="{{ asset('/assets/images/icon.png') }}" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/b719ed3eb8.js" crossorigin="anonymous"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


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
      justify-content: space-between;
      padding: 0.875rem 1.5rem;
      color: #495057;
      text-decoration: none;
      transition: all 0.2s ease;
      border-left: 3px solid transparent;
      position: relative;
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

    .nav-link-content {
      display: flex;
      align-items: center;
      flex: 1;
    }

    /* Notification Styles */
    .notification-badge {
      background-color: #dc3545;
      color: white;
      border-radius: 50%;
      width: 18px;
      height: 18px;
      font-size: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      animation: pulse 2s infinite;
      margin-left: 8px;
    }

    .notification-dot {
      width: 8px;
      height: 8px;
      background-color: #dc3545;
      border-radius: 50%;
      border: 2px solid white;
      animation: pulse 2s infinite;
      margin-left: 8px;
    }

    @keyframes pulse {
      0% {
        transform: scale(1);
        opacity: 1;
      }

      50% {
        transform: scale(1.1);
        opacity: 0.7;
      }

      100% {
        transform: scale(1);
        opacity: 1;
      }
    }

    /* Notification Panel */
    .notification-panel {
      position: fixed;
      top: 20px;
      right: 20px;
      background: white;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      padding: 1rem;
      max-width: 300px;
      z-index: 1050;
      transform: translateX(100%);
      transition: transform 0.3s ease;
    }

    .notification-panel.show {
      transform: translateX(0);
    }

    .notification-item {
      padding: 0.5rem 0;
      border-bottom: 1px solid #f0f0f0;
    }

    .notification-item:last-child {
      border-bottom: none;
    }

    .notification-title {
      font-weight: 500;
      font-size: 0.9rem;
      margin-bottom: 0.25rem;
    }

    .notification-time {
      font-size: 0.75rem;
      color: #6c757d;
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

    /* Notification Bell */
    .notification-bell {
      position: relative;
      cursor: pointer;
      margin-right: 1rem;
    }

    .notification-bell .notification-dot {
      position: absolute;
      top: -2px;
      right: -2px;
      margin: 0;
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

      .notification-panel {
        right: 10px;
        left: 10px;
        max-width: none;
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

    /* Success message */
    .success-message {
      position: fixed;
      top: 20px;
      right: 20px;
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
      border-radius: 4px;
      padding: 0.75rem 1rem;
      z-index: 1060;
      transform: translateX(100%);
      transition: transform 0.3s ease;
    }

    .success-message.show {
      transform: translateX(0);
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

  <!-- Notification Panel -->
  <div class="notification-panel" id="notificationPanel">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h6 class="mb-0">Notifications</h6>
      <button class="btn-close" id="closeNotifications"></button>
    </div>
    <div id="notificationList">
      @if(!empty($nouvellesAnnonces) && $nouvellesAnnonces > 0)
      <div class="notification-item">
        <div class="notification-title">Nouvelles annonces</div>
        <div class="notification-time">{{ $nouvellesAnnonces }} nouvelle(s) annonce(s)</div>
      </div>
      @else
      <div class="notification-item">
        <div class="notification-title">Aucune nouvelle notification</div>
      </div>
      @endif
    </div>
    <div class="mt-2">
      <button class="btn btn-sm btn-outline-primary w-100" onclick="markAllAsRead()">
        Marquer tout comme lu
      </button>
    </div>
  </div>
  <!-- Success Message -->
  <div class="success-message" id="successMessage">
    <i class="bi bi-check-circle me-2"></i>
    <span id="successText">Notifications marquées comme lues</span>
  </div>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <div class="logo-container">
        <a href="{{ route('dashboard.etudiant') }}">
          <img src="{{ asset('images/logo.png') }}" class="sidebar-logo" alt="Logo gestion ABS">
        </a>
        <div class="logo-text">
          Espace Étudiant<br>
          <small class="text-muted">absENS</small>
        </div>
      </div>
    </div>

    <ul class="nav-menu">
      <li class="nav-item {{ request()->routeIs('dashboard.etudiant') ? 'active' : '' }}">
        <a href="{{ route('dashboard.etudiant') }}" class="nav-link">
          <div class="nav-link-content">
            <i class="bi bi-house"></i> Tableau de bord
          </div>
        </a>
      </li>

      <li class="nav-item {{ request()->routeIs('emploi.etudiant') ? 'active' : '' }}">
        <a href="{{ route('emploi.etudiant') }}" class="nav-link">
          <div class="nav-link-content">
            <i class="bi bi-calendar-week"></i> Emploi du temps
          </div>
        </a>
      </li>

      <li class="nav-item {{ request()->routeIs('etudiant.qr-code') ? 'active' : '' }}">
        <a href="{{ route('etudiant.qr-code') }}" class="nav-link">
          <div class="nav-link-content">
            <i class="bi bi-qr-code-scan"></i> Mon QR Code
          </div>
        </a>
      </li>

      <li class="nav-item {{ request()->routeIs('etudiant.absences') ? 'active' : '' }}">
        <a href="{{ route('etudiant.absences') }}" class="nav-link">
          <div class="nav-link-content">
            <i class="bi bi-clipboard-check"></i> Mes absences
          </div>
        </a>
      </li>

      <li class="nav-item {{ request()->routeIs('annonces.miennes') ? 'active' : '' }}">
        <a href="{{ route('annonces.miennes') }}" class="nav-link">
          <div class="nav-link-content">
            <i class="bi bi-megaphone"></i> Annonces
          </div>
          @if(!empty($nouvellesAnnonces) && $nouvellesAnnonces > 0)
          <span class="notification-badge" id="announcementBadge">{{ $nouvellesAnnonces > 99 ? '99+' : $nouvellesAnnonces }}</span>
          @endif
        </a>
      </li>

      <li class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
        <a href="{{ route('profile.edit') }}" class="nav-link">
          <div class="nav-link-content">
            <i class="bi bi-person-circle"></i> Mon profil
          </div>
        </a>
      </li>
    </ul>

    <div class="sidebar-footer">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-danger w-100">
          <i class="bi bi-box-arrow-right me-2"></i> Se déconnecter
        </button>
      </form>
    </div>
  </div>

  <!-- Page Container -->
  <div class="page-container">
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
      <div class="container-fluid justify-content-end">
        <ul class="navbar-nav align-items-center">
          <!-- Notification Bell -->
          <li class="nav-item">
            <div class="notification-bell" onclick="toggleNotificationPanel()">
              <i class="bi bi-bell fs-5"></i>
              @if(!empty($nouvellesAnnonces) && $nouvellesAnnonces > 0)
              <span class="notification-dot"></span>
              @endif
            </div>
          </li>

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
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                  </button>
                </form>
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
                <a href="{{ route('dashboard.etudiant') }}">Accueil</a>
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

      // Close notification panel when clicking close button
      document.getElementById("closeNotifications")?.addEventListener("click", function() {
        document.getElementById("notificationPanel").classList.remove("show");
      });
    });

    // Toggle notification panel
    function toggleNotificationPanel() {
      const panel = document.getElementById("notificationPanel");
      panel.classList.toggle("show");
    }

    // Mark all notifications as read
    function markAllAsRead() {
      fetch('/mark-notifications-read', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Hide notification badges
            const badge = document.getElementById("announcementBadge");
            if (badge) badge.style.display = 'none';

            const dot = document.querySelector(".notification-bell .notification-dot");
            if (dot) dot.style.display = 'none';

            // Update notification list
            document.getElementById("notificationList").innerHTML =
              '<div class="notification-item"><div class="notification-title">Aucune nouvelle notification</div></div>';

            showSuccessMessage('Toutes les notifications ont été marquées comme lues');

            // Close notification panel
            document.getElementById("notificationPanel").classList.remove("show");
          }
        })
        .catch(error => {
          console.error('Erreur:', error);
        });
    }

    // Check for new announcements


    // Update notification badges
    function updateNotificationBadges(count) {
      const badge = document.getElementById("announcementBadge");
      const dot = document.querySelector(".notification-bell .notification-dot");

      if (count > 0) {
        if (badge) {
          badge.textContent = count > 99 ? '99+' : count;
          badge.style.display = 'flex';
        }
        if (dot) {
          dot.style.display = 'block';
        }
      } else {
        if (badge) badge.style.display = 'none';
        if (dot) dot.style.display = 'none';
      }
    }

    // Show success message
    function showSuccessMessage(message) {
      const successMsg = document.getElementById("successMessage");
      const successText = document.getElementById("successText");

      successText.textContent = message;
      successMsg.classList.add("show");

      setTimeout(() => {
        successMsg.classList.remove("show");
      }, 3000);
    }

    // Close notification panel when clicking outside
    document.addEventListener('click', function(event) {
      const panel = document.getElementById("notificationPanel");
      const bell = document.querySelector(".notification-bell");

      if (!panel.contains(event.target) && !bell.contains(event.target)) {
        panel.classList.remove("show");
      }
    });
  </script>

  @livewireScripts
  @yield('scripts')
  @stack('scripts')
</body>

</html>