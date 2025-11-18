@extends('layouts.'.$menu)
@section('breadcrumb', 'Gestion des utilisateurs')

@section('content')
<div class="container-fluid py-4">
  <!-- Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-lg">
        <div class="card-body bg-gradient-primary text-white py-4 px-4 rounded">
          <div class="d-flex align-items-center">
            <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
              <i class="bi bi-people-fill fs-2 text-white"></i>
            </div>
            <div>
              <h1 class="mb-0 fw-bold">Gestion des utilisateurs</h1>
              <p class="mb-0 opacity-75">Gérez les professeurs, étudiants et administrateurs</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Success Alert -->
  @if(session()->has('success'))
  <div class="row mb-4">
    <div class="col-12">
      <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <div class="d-flex align-items-center">
          <i class="bi bi-check-circle-fill fs-4 me-3"></i>
          <div>
            <strong>Succès!</strong> {{ session('success') }}
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
  </div>
  @endif

  <!-- Navigation Tabs -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-3">
          <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
              <a class="nav-link {{ $activeTab == 'professeurs' ? 'active' : '' }} rounded-pill mx-1"
                href="{{ route('gestion.utilisateurs', ['tab' => 'professeurs']) }}">
                <i class="bi bi-person-badge me-2"></i>
                <span class="d-none d-sm-inline">Professeurs</span>
                <span class="d-sm-none">Prof.</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ $activeTab == 'etudiants' ? 'active' : '' }} rounded-pill mx-1"
                href="{{ route('gestion.utilisateurs', ['tab' => 'etudiants']) }}">
                <i class="bi bi-mortarboard me-2"></i>
                <span class="d-none d-sm-inline">Étudiants</span>
                <span class="d-sm-none">Étud.</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ $activeTab == 'admins' ? 'active' : '' }} rounded-pill mx-1"
                href="{{ route('gestion.utilisateurs', ['tab' => 'admins']) }}">
                <i class="bi bi-shield-lock me-2"></i>
                <span class="d-none d-sm-inline">Admins</span>
                <span class="d-sm-none">Admin</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Tab Content -->
  <div class="tab-content">
    {{-- Onglet Professeurs --}}
    @if($activeTab == 'professeurs')
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-light border-0 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
              <div class="mb-2 mb-md-0">
                <h5 class="mb-1 text-primary fw-bold">
                  <i class="bi bi-person-badge me-2"></i>Liste des professeurs
                </h5>
                <small class="text-muted">{{ $professeurs->total() }} professeur(s) au total</small>
              </div>
              <a href="{{ route('professeurs.create') }}" class="btn btn-success rounded-pill">
                <i class="bi bi-person-plus me-2"></i>Ajouter un professeur
              </a>
            </div>
          </div>

          <div class="card-body p-4">
            <!-- Search and Export -->
            <div class="row g-3 mb-4">
              <div class="col-12 col-lg-6">
                <form method="GET" action="{{ route('gestion.utilisateurs') }}">
                  <input type="hidden" name="tab" value="professeurs">
                  <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                      <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search_prof" value="{{ request('search_prof') }}"
                      class="form-control border-start-0"
                      placeholder="Rechercher un professeur...">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                  </div>
                </form>
              </div>
              <div class="col-12 col-lg-6">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                  <a href="{{ route('gestion.utilisateurs', ['tab' => 'professeurs']) }}"
                    class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                  </a>
                  <a href="{{ route('professeurs.export') }}" class="btn btn-outline-success">
                    <i class="bi bi-file-earmark-excel me-1"></i>CSV
                  </a>
                  <a href="{{ route('admins.exportProfesseursToPDF') }}" class="btn btn-outline-danger">
                    <i class="bi bi-file-earmark-pdf me-1"></i>PDF
                  </a>
                </div>
              </div>
            </div>

            <!-- Bulk Actions -->
            <form id="bulkActionFormProf" method="POST" action="{{ route('professeurs.bulkAction') }}">
              @csrf
              <input type="hidden" name="action" id="bulkActionTypeProf">

              <div class="d-flex flex-wrap gap-2 mb-3">
                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill"
                  onclick="submitBulkActionProf('delete')">
                  <i class="bi bi-trash me-1"></i>Supprimer sélection
                </button>
                <button type="button" class="btn btn-outline-warning btn-sm rounded-pill"
                  onclick="submitBulkActionProf('reset')">
                  <i class="bi bi-key me-1"></i>Reset mots de passe
                </button>
              </div>

              <!-- Table -->
              <div class="table-responsive">
                <table class="table table-hover align-middle" id="tableProfesseurs">
                  <thead class="table-primary">
                    <tr>
                      <th class="text-center">
                        <input type="checkbox" id="selectAllProfesseurs" class="form-check-input">
                      </th>
                      <th>N°</th>
                      <th>Nom</th>
                      <th>Prénom</th>
                      <th class="d-none d-md-table-cell">Email</th>
                      <th class="d-none d-lg-table-cell">Date de naissance</th>
                      <th class="text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $i = ($professeurs->currentPage() - 1) * $professeurs->perPage() + 1; @endphp
                    @foreach($professeurs as $prof)
                    <tr>
                      <td class="text-center">
                        <input type="checkbox" name="selected[]" value="{{ $prof->user->id }}" class="form-check-input">
                      </td>
                      <td><span class="badge bg-secondary">{{ $i++ }}</span></td>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-person-fill text-primary"></i>
                          </div>
                          <div>
                            <div class="fw-bold">{{ $prof->user->name }}</div>
                          </div>
                      </td>
                      <td>
                        <div class="d-flex align-items-center">
                          <div>
                            <div class="fw-bold">{{ $prof->user->prenom }}</div>
                          </div>
                      </td>
                      <td class="d-none d-md-table-cell">
                        <span class="text-muted">{{ $prof->user->email }}</span>
                      </td>
                      <td class="d-none d-lg-table-cell">
                        {{ $prof->user->date_naissance ? \Carbon\Carbon::parse($prof->user->date_naissance)->format('d/m/Y') : '-' }}
                      </td>
                      <td>
                        <div class="d-flex gap-1 justify-content-center">
                          <a href="{{ route('professeurs.edit', $prof->id) }}"
                            class="btn btn-sm btn-outline-primary rounded-pill" title="Modifier">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <button type="button" class="btn btn-sm btn-outline-danger rounded-pill"
                            data-bs-toggle="modal" data-bs-target="#confirmDeleteProf{{ $prof->id }}"
                            title="Supprimer">
                            <i class="bi bi-trash"></i>
                          </button>
                          <form action="{{ route('utilisateur.resetPassword', $prof->user->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-warning rounded-pill" title="Reset mot de passe">
                              <i class="bi bi-key"></i>
                            </button>
                          </form>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="confirmDeleteProf{{ $prof->id }}" tabindex="-1">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">
                                  <i class="bi bi-exclamation-triangle me-2"></i>Confirmation
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                              </div>
                              <div class="modal-body text-center">
                                <i class="bi bi-person-x fs-1 text-danger mb-3"></i>
                                <p>Êtes-vous sûr de vouloir supprimer</p>
                                <strong>{{ $prof->user->name }} {{ $prof->user->prenom }}</strong> ?
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <form action="{{ route('professeurs.destroy', $prof->id) }}" method="POST" class="d-inline">
                                  @csrf @method('DELETE')
                                  <button type="submit" class="btn btn-danger">Confirmer</button>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              <div class="d-flex justify-content-center mt-4">
                {{ $professeurs->appends(request()->query())->links() }}
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    @endif

    {{-- Onglet Étudiants --}}
    @if($activeTab == 'etudiants')
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-light border-0 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
              <div class="mb-2 mb-md-0">
                <h5 class="mb-1 text-primary fw-bold">
                  <i class="bi bi-mortarboard me-2"></i>Liste des étudiants
                </h5>
                <small class="text-muted">{{ $etudiantsParGroupe->total() }} étudiant(s) au total</small>
              </div>
              <a href="{{ route('etudiants.create') }}" class="btn btn-success rounded-pill">
                <i class="bi bi-person-plus me-2"></i>Ajouter un étudiant
              </a>
            </div>
          </div>

          <div class="card-body p-4">
            <!-- Filters -->
            <form method="GET" action="{{ route('gestion.utilisateurs') }}" class="mb-4">
              <input type="hidden" name="tab" value="etudiants">
              <div class="row g-3">
                <div class="col-12 col-md-6 col-lg-4">
                  <div class="input-group">
                    <span class="input-group-text bg-light">
                      <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search_etud" value="{{ request('search_etud') }}"
                      class="form-control" placeholder="Rechercher un étudiant...">
                  </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                  <select name="filiere_id" class="form-select">
                    <option value="">-- Toutes les filières --</option>
                    @foreach($filieres as $filiere)
                    <option value="{{ $filiere->id }}" {{ request('filiere_id') == $filiere->id ? 'selected' : '' }}>
                      {{ $filiere->nom_filiere }}
                    </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                  <select name="groupe_id" class="form-select">
                    <option value="">-- Tous les groupes --</option>
                    @foreach($groupes as $groupe)
                    <option value="{{ $groupe->id }}" {{ request('groupe_id') == $groupe->id ? 'selected' : '' }}>
                      {{ $groupe->nom_groupe }}
                    </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-md-6 col-lg-2">
                  <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-primary flex-fill">
                      <i class="bi bi-search"></i>
                    </button>
                    <a href="{{ route('gestion.utilisateurs', ['tab' => 'etudiants']) }}"
                      class="btn btn-outline-secondary">
                      <i class="bi bi-arrow-clockwise"></i>
                    </a>
                  </div>
                </div>
              </div>
            </form>

            <!-- Export Buttons -->
            <div class="d-flex flex-wrap gap-2 mb-3">
              <a href="{{ route('etudiants.export') }}" class="btn btn-outline-success btn-sm">
                <i class="bi bi-file-earmark-excel me-1"></i>CSV
              </a>
              <a href="{{ route('admins.exportEtudiantsToPDF') }}" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i>PDF
              </a>
            </div>

            <!-- Bulk Actions -->
            <form id="bulkActionForm" method="POST" action="{{ route('etudiants.bulkAction') }}">
              @csrf
              <input type="hidden" name="action" id="bulkActionType">

              <div class="d-flex flex-wrap gap-2 mb-3">
                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill"
                  onclick="submitBulkAction('delete')">
                  <i class="bi bi-trash me-1"></i>Supprimer sélection
                </button>
                <button type="button" class="btn btn-outline-warning btn-sm rounded-pill"
                  onclick="submitBulkAction('reset')">
                  <i class="bi bi-key me-1"></i>Reset mots de passe
                </button>
              </div>

              <!-- Table -->
              <div class="table-responsive">
                <table class="table table-hover align-middle" id="tableEtudiants">
                  <thead class="table-primary">
                    <tr>
                      <th class="text-center">
                        <input type="checkbox" id="selectAllEtudiants" class="form-check-input">
                      </th>
                      <th>N°</th>
                      <th>Nom</th>
                      <th>Prénom</th>
                      <th class="d-none d-md-table-cell">CNE</th>
                      <th class="d-none d-lg-table-cell">Email</th>
                      <th class="d-none d-lg-table-cell">Date naissance</th>
                      <th>Groupe/Filière</th>
                      <th class="text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($etudiantsParGroupe as $groupe)
                    @if($groupe->nom_groupe === 'Aucun')
                    <tr class="table-warning">
                      <td colspan="8" class="fw-bold text-center py-3">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        Étudiants sans groupe
                      </td>
                    </tr>
                    @else
                    <tr class="table-info">
                      <td colspan="8" class="fw-bold text-center py-3">
                        <i class="bi bi-collection me-2"></i>
                        Filière : {{ $groupe->filiere->nom_filiere ?? 'Non définie' }} —
                        Groupe : {{ $groupe->nom_groupe }}
                      </td>
                    </tr>
                    @endif

                    @foreach($groupe->etudiants as $index => $etudiant)
                    <tr>
                      <td class="text-center">
                        <input type="checkbox" name="selected[]" value="{{ $etudiant->user->id }}" class="form-check-input">
                      </td>
                      <td><span class="badge bg-secondary">{{ $index + 1 }}</span></td>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="me-3">
                            @if (!empty($etudiant->user->photo) && file_exists(storage_path('app/public/' . $etudiant->user->photo)))
                            <img src="{{ Storage::url($etudiant->user->photo) }}"
                              alt="Photo de {{ $etudiant->user->name }}"
                              class="rounded-circle cursor-pointer"
                              style="width: 40px; height: 40px; object-fit: cover;"
                              onclick='showPhotoModal("{{ Storage::url($etudiant->user->photo) }}")'>
                            @else
                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                              style="width: 40px; height: 40px; font-size: 0.8rem;">
                              @if($groupe->nom_groupe === 'Aucun')
                              <i class="bi bi-person-dash text-danger"></i>
                              @else
                              {{ strtoupper(substr($etudiant->user->name ?? '', 0, 1)) }}{{ strtoupper(substr($etudiant->user->prenom ?? '', 0, 1)) }}
                              @endif
                            </div>
                            @endif
                          </div>
                          <div>
                            <div class="fw-bold">{{ $etudiant->user->name }}</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="d-flex align-items-center">
                          <div>
                            <div class="fw-bold">{{ $etudiant->user->prenom }}</div>
                          </div>
                        </div>
                      </td>
                      <td class="d-none d-md-table-cell">
                        <span class="badge bg-light text-dark">{{ $etudiant->cne }}</span>
                      </td>
                      <td class="d-none d-lg-table-cell">
                        <span class="text-muted">{{ $etudiant->user->email }}</span>
                      </td>
                      <td class="d-none d-lg-table-cell">
                        {{ $etudiant->user->date_naissance ? \Carbon\Carbon::parse($etudiant->user->date_naissance)->format('d/m/Y') : '-' }}
                      </td>
                      <td>
                        <div class="d-flex flex-column gap-1">
                          <span class="badge bg-secondary">{{ $groupe->nom_groupe }}</span>
                          <span class="badge bg-info">{{ $groupe->filiere->nom_filiere ?? '-' }}</span>
                        </div>
                      </td>
                      <td>
                        <div class="d-flex gap-1 justify-content-center">
                          <a href="{{ route('etudiants.edit', $etudiant->id) }}"
                            class="btn btn-sm btn-outline-primary rounded-pill" title="Modifier">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <button type="button" class="btn btn-sm btn-outline-danger rounded-pill"
                            data-bs-toggle="modal" data-bs-target="#confirmDeleteEtudiant{{ $etudiant->id }}"
                            title="Supprimer">
                            <i class="bi bi-trash"></i>
                          </button>
                          <form action="{{ route('utilisateur.resetPassword', $etudiant->user->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-warning rounded-pill" title="Reset mot de passe">
                              <i class="bi bi-key"></i>
                            </button>
                          </form>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="confirmDeleteEtudiant{{ $etudiant->id }}" tabindex="-1">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">
                                  <i class="bi bi-exclamation-triangle me-2"></i>Confirmation
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                              </div>
                              <div class="modal-body text-center">
                                <i class="bi bi-person-x fs-1 text-danger mb-3"></i>
                                <p>Êtes-vous sûr de vouloir supprimer</p>
                                <strong>{{ $etudiant->user->name }} {{ $etudiant->user->prenom }}</strong> ?
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <form action="{{ route('etudiants.destroy', $etudiant->id) }}" method="POST" class="d-inline">
                                  @csrf @method('DELETE')
                                  <button type="submit" class="btn btn-danger">Confirmer</button>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                    @endforeach
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              <div class="d-flex justify-content-center mt-4">
                {{ $etudiantsParGroupe->withQueryString()->links() }}
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    @endif

    {{-- Onglet Admins --}}
    @if($activeTab == 'admins')
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-light border-0 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
              <div class="mb-2 mb-md-0">
                <h5 class="mb-1 text-primary fw-bold">
                  <i class="bi bi-shield-lock me-2"></i>Liste des administrateurs
                </h5>
                <small class="text-muted">{{ $administrateurs->total() }} administrateur(s) au total</small>
              </div>
              <a href="{{ route('admins.create') }}" class="btn btn-success rounded-pill">
                <i class="bi bi-person-plus me-2"></i>Ajouter un admin
              </a>
            </div>
          </div>

          <div class="card-body p-4">
            <!-- Search and Export -->
            <div class="row g-3 mb-4">
              <div class="col-12 col-lg-6">
                <form method="GET" action="{{ route('gestion.utilisateurs') }}">
                  <input type="hidden" name="tab" value="admins">
                  <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                      <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search_admin" value="{{ request('search_admin') }}"
                      class="form-control border-start-0"
                      placeholder="Rechercher un administrateur...">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                  </div>
                </form>
              </div>
              <div class="col-12 col-lg-6">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                  <a href="{{ route('gestion.utilisateurs', ['tab' => 'admins']) }}"
                    class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                  </a>
                  <a href="{{ route('admins.export') }}" class="btn btn-outline-success">
                    <i class="bi bi-file-earmark-excel me-1"></i>CSV
                  </a>
                </div>
              </div>
            </div>

            <!-- Bulk Actions -->
            <form id="bulkActionFormAdmin" method="POST" action="{{ route('admins.bulkAction') }}">
              @csrf
              <input type="hidden" name="action" id="bulkActionTypeAdmin">

              <div class="d-flex flex-wrap gap-2 mb-3">
                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill"
                  onclick="submitBulkActionAdmin('delete')">
                  <i class="bi bi-trash me-1"></i>Supprimer sélection
                </button>
                <button type="button" class="btn btn-outline-warning btn-sm rounded-pill"
                  onclick="submitBulkActionAdmin('reset')">
                  <i class="bi bi-key me-1"></i>Reset mots de passe
                </button>
              </div>

              <!-- Table -->
              <div class="table-responsive">
                <table class="table table-hover align-middle" id="tableAdmins">
                  <thead class="table-primary">
                    <tr>
                      <th class="text-center">
                        <input type="checkbox" id="selectAllAdmins" class="form-check-input">
                      </th>
                      <th>N°</th>
                      <th>Nom complet</th>
                      <th class="d-none d-md-table-cell">Email</th>
                      <th class="d-none d-lg-table-cell">Date de naissance</th>
                      <th class="text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $i = ($administrateurs->currentPage() - 1) * $administrateurs->perPage() + 1; @endphp
                    @foreach($administrateurs as $admin)
                    <tr>
                      <td class="text-center">
                        <input type="checkbox" name="selected[]" value="{{ $admin->user->id }}" class="form-check-input">
                      </td>
                      <td><span class="badge bg-secondary">{{ $i++ }}</span></td>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-shield-lock-fill text-warning"></i>
                          </div>
                          <div>
                            <div class="fw-bold">{{ $admin->user->name }}</div>
                            <small class="text-muted">{{ $admin->user->prenom }}</small>
                            <div class="d-md-none">
                              <small class="text-muted">{{ $admin->user->email }}</small>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="d-none d-md-table-cell">
                        <span class="text-muted">{{ $admin->user->email }}</span>
                      </td>
                      <td class="d-none d-lg-table-cell">
                        {{ $admin->user->date_naissance ? \Carbon\Carbon::parse($admin->user->date_naissance)->format('d/m/Y') : '-' }}
                      </td>
                      <td>
                        <div class="d-flex gap-1 justify-content-center">
                          <a href="{{ route('admins.edit', $admin->id) }}"
                            class="btn btn-sm btn-outline-primary rounded-pill" title="Modifier">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <button type="button" class="btn btn-sm btn-outline-danger rounded-pill"
                            data-bs-toggle="modal" data-bs-target="#confirmDeleteAdmin{{ $admin->id }}"
                            title="Supprimer">
                            <i class="bi bi-trash"></i>
                          </button>
                          <form action="{{ route('utilisateur.resetPassword', $admin->user->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-warning rounded-pill" title="Reset mot de passe">
                              <i class="bi bi-key"></i>
                            </button>
                          </form>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="confirmDeleteAdmin{{ $admin->id }}" tabindex="-1">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">
                                  <i class="bi bi-exclamation-triangle me-2"></i>Confirmation
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                              </div>
                              <div class="modal-body text-center">
                                <i class="bi bi-person-x fs-1 text-danger mb-3"></i>
                                <p>Êtes-vous sûr de vouloir supprimer</p>
                                <strong>{{ $admin->user->name }} {{ $admin->user->prenom }}</strong> ?
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <form action="{{ route('admins.destroy', $admin->id) }}" method="POST" class="d-inline">
                                  @csrf @method('DELETE')
                                  <button type="submit" class="btn btn-danger">Confirmer</button>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              <div class="d-flex justify-content-center mt-4">
                {{ $administrateurs->appends(request()->query())->links() }}
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    @endif
  </div>
</div>

<!-- Modals -->
<!-- Modal pour afficher la photo -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 overflow-hidden">
      <div class="modal-body p-0">
        <img id="modalPhoto" src="" alt="Photo étudiante" class="img-fluid w-100">
      </div>
    </div>
  </div>
</div>
<!-- Selection Required Modal -->
<div class="modal fade" id="modalSelectionRequired" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title">
          <i class="bi bi-exclamation-triangle me-2"></i>Action impossible
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <i class="bi bi-info-circle fs-1 text-warning mb-3"></i>
        <p>Veuillez sélectionner au moins un utilisateur.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- Bulk Delete Confirmation Modal -->
<div class="modal fade" id="modalConfirmBulkDelete" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">
          <i class="bi bi-trash me-2"></i>Confirmer la suppression
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <i class="bi bi-exclamation-triangle fs-1 text-danger mb-3"></i>
        <p>Êtes-vous sûr de vouloir supprimer les utilisateurs sélectionnés ?</p>
        <div class="alert alert-warning">
          <strong>Cette action est irréversible.</strong>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger" id="confirmBulkDeleteBtn">Confirmer</button>
      </div>
    </div>
  </div>
</div>

<!-- Bulk Reset Confirmation Modal -->
<div class="modal fade" id="modalConfirmBulkReset" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title">
          <i class="bi bi-key me-2"></i>Confirmer le reset des mots de passe
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <i class="bi bi-key fs-1 text-warning mb-3"></i>
        <p>Êtes-vous sûr de vouloir réinitialiser les mots de passe des utilisateurs sélectionnés ?</p>
        <div class="alert alert-info">
          <strong>Les utilisateurs recevront un nouveau mot de passe temporaire.</strong>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-warning" id="confirmBulkResetBtn">Confirmer</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  function showPhotoModal(photoUrl) {
    document.getElementById('modalPhoto').src = photoUrl;
    const modal = new bootstrap.Modal(document.getElementById('photoModal'));
    modal.show();
  }
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    let pendingBulkAction = null;

    // Select All functionality
    function setupSelectAll(selectAllId, tableSelector) {
      const selectAll = document.getElementById(selectAllId);
      if (!selectAll) return;

      selectAll.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll(`${tableSelector} input[name="selected[]"]`);
        checkboxes.forEach(cb => cb.checked = this.checked);
      });

      const checkboxes = document.querySelectorAll(`${tableSelector} input[name="selected[]"]`);
      checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
          const allChecked = Array.from(checkboxes).every(cb => cb.checked);
          const someChecked = Array.from(checkboxes).some(cb => cb.checked);
          selectAll.checked = allChecked;
          selectAll.indeterminate = someChecked && !allChecked;
        });
      });
    }

    // Setup for all tables
    setupSelectAll('selectAllEtudiants', '#tableEtudiants');
    setupSelectAll('selectAllProfesseurs', '#tableProfesseurs');
    setupSelectAll('selectAllAdmins', '#tableAdmins');

    // Bulk Actions
    window.submitBulkAction = function(actionType) {
      handleBulkAction('bulkActionForm', 'bulkActionType', '#tableEtudiants', actionType);
    };

    window.submitBulkActionProf = function(actionType) {
      handleBulkAction('bulkActionFormProf', 'bulkActionTypeProf', '#tableProfesseurs', actionType);
    };

    window.submitBulkActionAdmin = function(actionType) {
      handleBulkAction('bulkActionFormAdmin', 'bulkActionTypeAdmin', '#tableAdmins', actionType);
    };

    function handleBulkAction(formId, actionInputId, tableSelector, actionType) {
      const selected = document.querySelectorAll(`${tableSelector} input[name="selected[]"]:checked`);
      const actionInput = document.getElementById(actionInputId);
      const form = document.getElementById(formId);

      if (selected.length === 0) {
        const modal = new bootstrap.Modal(document.getElementById('modalSelectionRequired'));
        modal.show();
        return;
      }

      if (actionType === 'delete') {
        pendingBulkAction = {
          formId,
          actionInputId,
          actionType
        };
        const confirmModal = new bootstrap.Modal(document.getElementById('modalConfirmBulkDelete'));
        confirmModal.show();
        return;
      }

      if (actionType === 'reset') {
        pendingBulkAction = {
          formId,
          actionInputId,
          actionType
        };
        const confirmModal = new bootstrap.Modal(document.getElementById('modalConfirmBulkReset'));
        confirmModal.show();
        return;
      }

      actionInput.value = actionType;
      form.submit();
    }

    // Confirm bulk delete
    const confirmBtn = document.getElementById('confirmBulkDeleteBtn');
    if (confirmBtn) {
      confirmBtn.addEventListener('click', () => {
        if (pendingBulkAction) {
          const actionInput = document.getElementById(pendingBulkAction.actionInputId);
          const form = document.getElementById(pendingBulkAction.formId);
          actionInput.value = pendingBulkAction.actionType;
          form.submit();
        }
      });
    }

    // Confirm bulk reset
    const confirmResetBtn = document.getElementById('confirmBulkResetBtn');
    if (confirmResetBtn) {
      confirmResetBtn.addEventListener('click', () => {
        if (pendingBulkAction) {
          const actionInput = document.getElementById(pendingBulkAction.actionInputId);
          const form = document.getElementById(pendingBulkAction.formId);
          actionInput.value = pendingBulkAction.actionType;
          form.submit();
        }
      });
    }

    // Auto-hide success alert
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
      setTimeout(() => {
        successAlert.classList.remove('show');
      }, 5000);
    }
  });
</script>
@endsection

@push('styles')
<style>
  /* Custom gradient */
  .bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  }

  /* Responsive table improvements */
  .table-responsive {
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  }

  /* Button improvements */
  .btn {
    transition: all 0.2s ease-in-out;
  }

  .btn:hover {
    transform: translateY(-1px);
  }

  /* Badge improvements */
  .badge {
    font-weight: 500;
  }

  /* Card improvements */
  .card {
    transition: all 0.3s ease;
  }

  .card:hover {
    transform: translateY(-2px);
  }

  /* Checkbox indeterminate state */
  input[type="checkbox"]:indeterminate {
    background-color: #0d6efd;
    border-color: #0d6efd;
  }

  input[type="checkbox"]:indeterminate::before {
    content: "−";
    color: white;
    font-weight: bold;
    display: block;
    text-align: center;
    line-height: 1;
  }

  /* Mobile responsiveness */
  @media (max-width: 768px) {
    .card-body {
      padding: 1rem !important;
    }

    .table {
      font-size: 0.875rem;
    }

    .btn-sm {
      font-size: 0.75rem;
      padding: 0.25rem 0.5rem;
    }

    .badge {
      font-size: 0.7rem;
    }
  }

  @media (max-width: 576px) {
    .nav-pills {
      flex-direction: column;
    }

    .nav-pills .nav-item {
      margin-bottom: 0.25rem;
    }

    .table {
      font-size: 0.8rem;
    }

    .modal-dialog {
      margin: 0.5rem;
    }
  }

  /* Animation for alerts */
  .alert {
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

  /* Loading state for buttons */
  .btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  /* Improved focus states */
  .form-control:focus,
  .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
  }

  /* Table hover effects */
  .table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.075);
    transform: scale(1.01);
    transition: all 0.2s ease;
  }
</style>
@endpush