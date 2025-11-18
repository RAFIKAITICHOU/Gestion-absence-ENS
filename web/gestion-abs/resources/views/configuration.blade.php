@extends('layouts.'.$menu)
@section('breadcrumb', 'Configuration')
@section('title', 'Configuration')

@section('content')
<div class="container-fluid py-4">
  <!-- Header Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-lg bg-gradient-config text-white rounded-4">
        <div class="card-body py-4 px-4">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h2 class="mb-1 fw-bold">
                <i class="bi bi-gear-fill me-3"></i>Configuration Système
              </h2>
              <p class="mb-0 opacity-75">Gestion centralisée des paramètres et données de base</p>
            </div>
            <div class="d-none d-md-block">
              <i class="bi bi-sliders display-4 opacity-25"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Alerts Section -->
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

  @if(session('error'))
  <div class="row mb-4">
    <div class="col-12">
      <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm" role="alert">
        <div class="d-flex align-items-center">
          <i class="bi bi-exclamation-circle-fill me-3 fs-4"></i>
          <div>
            <strong>Erreur !</strong> {{ session('error') }}
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
  </div>
  @endif

  <!-- Statistics Cards -->
  <div class="row mb-4">
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-4 stat-card bg-primary text-white">
        <div class="card-body text-center py-3">
          <i class="bi bi-diagram-3 fs-2 mb-2"></i>
          <h4 class="mb-0 fw-bold">{{ $filieres->count() }}</h4>
          <small class="opacity-75">Filières</small>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-4 stat-card bg-success text-white">
        <div class="card-body text-center py-3">
          <i class="bi bi-people fs-2 mb-2"></i>
          <h4 class="mb-0 fw-bold">{{ \App\Models\Groupe::count() }}</h4>
          <small class="opacity-75">Groupes</small>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-4 stat-card bg-info text-white">
        <div class="card-body text-center py-3">
          <i class="bi bi-building fs-2 mb-2"></i>
          <h4 class="mb-0 fw-bold">{{ \App\Models\Salle::count() }}</h4>
          <small class="opacity-75">Salles</small>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-4 stat-card bg-warning text-dark">
        <div class="card-body text-center py-3">
          <i class="bi bi-calendar-x fs-2 mb-2"></i>
          <h4 class="mb-0 fw-bold">{{ \App\Models\JourInactif::count() }}</h4>
          <small class="opacity-75">Vacances</small>
        </div>
      </div>
    </div>
  </div>

  <!-- Navigation Tabs -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-0">
          <ul class="nav nav-pills nav-pills-custom p-3 mb-0" role="tablist">
            <li class="nav-item me-2">
              <a class="nav-link rounded-pill {{ session('active_tab', 'tab-filiere') == 'tab-filiere' ? 'active' : '' }}"
                data-bs-toggle="tab" href="#tab-filiere">
                <i class="bi bi-diagram-3 me-2"></i>
                <span class="d-none d-sm-inline">Filières</span>
              </a>
            </li>
            <li class="nav-item me-2">
              <a class="nav-link rounded-pill {{ session('active_tab') == 'tab-groupe' ? 'active' : '' }}"
                data-bs-toggle="tab" href="#tab-groupe">
                <i class="bi bi-people me-2"></i>
                <span class="d-none d-sm-inline">Groupes</span>
              </a>
            </li>
            <li class="nav-item me-2">
              <a class="nav-link rounded-pill {{ session('active_tab') == 'tab-salle' ? 'active' : '' }}"
                data-bs-toggle="tab" href="#tab-salle">
                <i class="bi bi-building me-2"></i>
                <span class="d-none d-sm-inline">Salles</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link rounded-pill {{ session('active_tab') == 'tab-vacance' ? 'active' : '' }}"
                data-bs-toggle="tab" href="#tab-vacance">
                <i class="bi bi-calendar-x me-2"></i>
                <span class="d-none d-sm-inline">Vacances</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Tab Content -->
  <div class="tab-content">
    {{-- ===== FILIÈRES ===== --}}
    <div class="tab-pane fade {{ session('active_tab', 'tab-filiere') == 'tab-filiere' ? 'show active' : '' }}" id="tab-filiere">
      <div class="row g-4">
        <!-- Formulaire d'ajout -->
        <div class="col-lg-5">
          <div class="card border-0 shadow-lg rounded-4 h-100">
            <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4 py-3">
              <h5 class="mb-0 fw-bold">
                <i class="bi bi-plus-circle me-2"></i>Nouvelle Filière
              </h5>
            </div>
            <div class="card-body p-4">
              <form action="{{ route('filieres.store') }}" method="POST" class="modern-form">
                @csrf
                <input type="hidden" name="active_tab" value="tab-filiere">

                <div class="mb-4">
                  <label class="form-label fw-semibold">
                    <i class="bi bi-tag me-1"></i>Nom de la filière
                  </label>
                  <input type="text" name="nom_filiere" class="form-control form-control-lg rounded-3 border-2"
                    placeholder="Ex: Informatique" required>
                </div>

                <div class="mb-4">
                  <label class="form-label fw-semibold">
                    <i class="bi bi-text-paragraph me-1"></i>Description
                  </label>
                  <textarea name="description" class="form-control rounded-3 border-2" rows="4"
                    placeholder="Description détaillée de la filière..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-lg rounded-pill w-100 shadow-sm">
                  <i class="bi bi-plus-lg me-2"></i>Ajouter la Filière
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Liste des filières -->
        <div class="col-lg-7">
          <div class="card border-0 shadow-lg rounded-4 h-100">
            <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4 py-3">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                  <i class="bi bi-list-ul me-2"></i>Liste des Filières
                </h5>
                <span class="badge bg-white text-primary fs-6">{{ $filieres->count() }} filière(s)</span>
              </div>
            </div>
            <div class="card-body p-0">
              @if($filieres->count() > 0)
              <div class="table-responsive">
                <table class="table table-hover modern-table mb-0">
                  <thead class="table-light">
                    <tr>
                      <th class="px-4 py-3 fw-bold">Filière</th>
                      <th class="px-4 py-3 fw-bold d-none d-md-table-cell">Description</th>
                      <th class="px-4 py-3 fw-bold text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($filieres as $filiere)
                    <tr class="table-row-hover">
                      <td class="px-4 py-3">
                        <div class="d-flex align-items-center">
                          <div class="icon-circle bg-primary me-3">
                            <i class="bi bi-diagram-3 text-white"></i>
                          </div>
                          <div>
                            <div class="fw-bold text-dark">{{ $filiere->nom_filiere }}</div>
                            <small class="text-muted d-md-none">{{ Str::limit($filiere->description, 30) }}</small>
                          </div>
                        </div>
                      </td>
                      <td class="px-4 py-3 text-muted d-none d-md-table-cell">
                        {{ $filiere->description ?: 'Aucune description' }}
                      </td>
                      <td class="px-4 py-3 text-center">
                        <div class="btn-group" role="group">
                          <a href="{{ route('filieres.edit', $filiere->id) }}"
                            class="btn btn-warning btn-sm rounded-pill me-1" title="Modifier">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <button type="button" class="btn btn-danger btn-sm rounded-pill"
                            onclick="openConfirmationModal('{{ route('filieres.destroy', $filiere->id) }}', 'Supprimer la filière {{ $filiere->nom_filiere }} ?')"
                            title="Supprimer">
                            <i class="bi bi-trash"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              @else
              <div class="text-center py-5">
                <i class="bi bi-diagram-3 display-4 text-muted mb-3"></i>
                <h5 class="text-muted">Aucune filière</h5>
                <p class="text-muted">Commencez par ajouter votre première filière</p>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ===== GROUPES ===== --}}
    <div class="tab-pane fade {{ session('active_tab') == 'tab-groupe' ? 'show active' : '' }}" id="tab-groupe">
      <div class="row g-4">
        <!-- Formulaire d'ajout -->
        <div class="col-lg-5">
          <div class="card border-0 shadow-lg rounded-4 h-100">
            <div class="card-header bg-gradient-success text-white border-0 rounded-top-4 py-3">
              <h5 class="mb-0 fw-bold">
                <i class="bi bi-plus-circle me-2"></i>Nouveau Groupe
              </h5>
            </div>
            <div class="card-body p-4">
              <form action="{{ route('groupes.store') }}" method="POST" class="modern-form">
                @csrf
                <input type="hidden" name="active_tab" value="tab-groupe">

                <div class="mb-4">
                  <label class="form-label fw-semibold">
                    <i class="bi bi-diagram-3 me-1"></i>Filière
                  </label>
                  <select name="filiere_id" class="form-select form-select-lg rounded-3 border-2" required>
                    <option value="">-- Sélectionner une filière --</option>
                    @foreach($filieres as $filiere)
                    <option value="{{ $filiere->id }}">{{ $filiere->nom_filiere }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="mb-4">
                  <label class="form-label fw-semibold">
                    <i class="bi bi-people me-1"></i>Nom du groupe
                  </label>
                  <input type="text" name="nom" class="form-control form-control-lg rounded-3 border-2"
                    placeholder="Ex: Groupe A" required>
                </div>

                <button type="submit" class="btn btn-success btn-lg rounded-pill w-100 shadow-sm">
                  <i class="bi bi-plus-lg me-2"></i>Ajouter le Groupe
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Liste des groupes -->
        <div class="col-lg-7">
          <div class="card border-0 shadow-lg rounded-4 h-100">
            <div class="card-header bg-gradient-success text-white border-0 rounded-top-4 py-3">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                  <i class="bi bi-list-ul me-2"></i>Liste des Groupes
                </h5>
                <span class="badge bg-white text-success fs-6">{{ \App\Models\Groupe::count() }} groupe(s)</span>
              </div>
            </div>
            <div class="card-body p-0">
              @php $groupes = \App\Models\Groupe::with('filiere')->get(); @endphp
              @if($groupes->count() > 0)
              <div class="table-responsive">
                <table class="table table-hover modern-table mb-0">
                  <thead class="table-light">
                    <tr>
                      <th class="px-4 py-3 fw-bold">Groupe</th>
                      <th class="px-4 py-3 fw-bold d-none d-md-table-cell">Filière</th>
                      <th class="px-4 py-3 fw-bold text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($groupes as $groupe)
                    <tr class="table-row-hover">
                      <td class="px-4 py-3">
                        <div class="d-flex align-items-center">
                          <div class="icon-circle bg-success me-3">
                            <i class="bi bi-people text-white"></i>
                          </div>
                          <div>
                            <div class="fw-bold text-dark">{{ $groupe->nom_groupe }}</div>
                            <small class="text-muted d-md-none">{{ $groupe->filiere->nom_filiere ?? 'N/A' }}</small>
                          </div>
                        </div>
                      </td>
                      <td class="px-4 py-3 d-none d-md-table-cell">
                        @if($groupe->filiere)
                        <span class="badge bg-primary rounded-pill">{{ $groupe->filiere->nom_filiere }}</span>
                        @else
                        <span class="text-muted">Non assigné</span>
                        @endif
                      </td>
                      <td class="px-4 py-3 text-center">
                        <div class="btn-group" role="group">
                          <a href="{{ route('groupes.edit', $groupe->id) }}"
                            class="btn btn-warning btn-sm rounded-pill me-1" title="Modifier">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <button type="button" class="btn btn-danger btn-sm rounded-pill"
                            onclick="openConfirmationModal('{{ route('groupes.destroy', $groupe->id) }}', 'Supprimer le groupe {{ $groupe->nom_groupe }} ?')"
                            title="Supprimer">
                            <i class="bi bi-trash"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              @else
              <div class="text-center py-5">
                <i class="bi bi-people display-4 text-muted mb-3"></i>
                <h5 class="text-muted">Aucun groupe</h5>
                <p class="text-muted">Ajoutez des groupes pour organiser vos étudiants</p>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ===== SALLES ===== --}}
    <div class="tab-pane fade {{ session('active_tab') == 'tab-salle' ? 'show active' : '' }}" id="tab-salle">
      <div class="row g-4">
        <!-- Formulaire d'ajout -->
        <div class="col-lg-5">
          <div class="card border-0 shadow-lg rounded-4 h-100">
            <div class="card-header bg-gradient-info text-white border-0 rounded-top-4 py-3">
              <h5 class="mb-0 fw-bold">
                <i class="bi bi-plus-circle me-2"></i>Nouvelle Salle
              </h5>
            </div>
            <div class="card-body p-4">
              <form action="{{ route('salles.store') }}" method="POST" class="modern-form">
                @csrf
                <input type="hidden" name="active_tab" value="tab-salle">

                <div class="mb-4">
                  <label class="form-label fw-semibold">
                    <i class="bi bi-building me-1"></i>Nom de la salle
                  </label>
                  <input type="text" name="nom" class="form-control form-control-lg rounded-3 border-2"
                    placeholder="Ex: Salle A101" required>
                </div>

                <div class="mb-4">
                  <label class="form-label fw-semibold">
                    <i class="bi bi-tools me-1"></i>Équipements
                  </label>
                  <textarea name="equipements" class="form-control rounded-3 border-2" rows="3"
                    placeholder="Ordinateurs, tableau interactif, climatisation..."></textarea>
                </div>

                <div class="mb-4">
                  <label class="form-label fw-semibold">
                    <i class="bi bi-projector me-1"></i>Projecteur disponible
                  </label>
                  <select name="projecteurs" class="form-select form-select-lg rounded-3 border-2" required>
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                  </select>
                </div>

                <button type="submit" class="btn btn-info btn-lg rounded-pill w-100 shadow-sm">
                  <i class="bi bi-plus-lg me-2"></i>Ajouter la Salle
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Liste des salles -->
        <div class="col-lg-7">
          <div class="card border-0 shadow-lg rounded-4 h-100">
            <div class="card-header bg-gradient-info text-white border-0 rounded-top-4 py-3">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                  <i class="bi bi-list-ul me-2"></i>Liste des Salles
                </h5>
                <span class="badge bg-white text-info fs-6">{{ \App\Models\Salle::count() }} salle(s)</span>
              </div>
            </div>
            <div class="card-body p-0">
              @php $salles = \App\Models\Salle::all(); @endphp
              @if($salles->count() > 0)
              <div class="table-responsive">
                <table class="table table-hover modern-table mb-0">
                  <thead class="table-light">
                    <tr>
                      <th class="px-4 py-3 fw-bold">Salle</th>
                      <th class="px-4 py-3 fw-bold text-center d-none d-md-table-cell">Projecteur</th>
                      <th class="px-4 py-3 fw-bold d-none d-lg-table-cell">Équipements</th>
                      <th class="px-4 py-3 fw-bold text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($salles as $salle)
                    <tr class="table-row-hover">
                      <td class="px-4 py-3">
                        <div class="d-flex align-items-center">
                          <div class="icon-circle bg-info me-3">
                            <i class="bi bi-building text-white"></i>
                          </div>
                          <div>
                            <div class="fw-bold text-dark">{{ $salle->nom }}</div>
                            <div class="d-md-none">
                              <small class="text-muted">{{ Str::limit($salle->equipements, 20) }}</small>
                              @if($salle->projecteurs)
                              <span class="badge bg-success ms-1">Projecteur</span>
                              @endif
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="px-4 py-3 text-center d-none d-md-table-cell">
                        @if($salle->projecteurs)
                        <span class="badge bg-success rounded-pill fs-6">
                          <i class="bi bi-check-lg me-1"></i>Oui
                        </span>
                        @else
                        <span class="badge bg-secondary rounded-pill fs-6">
                          <i class="bi bi-x-lg me-1"></i>Non
                        </span>
                        @endif
                      </td>
                      <td class="px-4 py-3 text-muted d-none d-lg-table-cell">
                        {{ $salle->equipements ?: 'Aucun équipement spécifié' }}
                      </td>
                      <td class="px-4 py-3 text-center">
                        <div class="btn-group" role="group">
                          <a href="{{ route('salles.edit', $salle->id) }}"
                            class="btn btn-warning btn-sm rounded-pill me-1" title="Modifier">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <button type="button" class="btn btn-danger btn-sm rounded-pill"
                            onclick="openConfirmationModal('{{ route('salles.destroy', $salle->id) }}', 'Supprimer la salle {{ $salle->nom }} ?')"
                            title="Supprimer">
                            <i class="bi bi-trash"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              @else
              <div class="text-center py-5">
                <i class="bi bi-building display-4 text-muted mb-3"></i>
                <h5 class="text-muted">Aucune salle</h5>
                <p class="text-muted">Ajoutez des salles pour organiser vos cours</p>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ===== VACANCES ===== --}}
    <div class="tab-pane fade {{ session('active_tab') == 'tab-vacance' ? 'show active' : '' }}" id="tab-vacance">
      <div class="row g-4">
        <!-- Formulaire d'ajout -->
        <div class="col-lg-5">
          <div class="card border-0 shadow-lg rounded-4 h-100">
            <div class="card-header bg-gradient-warning text-dark border-0 rounded-top-4 py-3">
              <h5 class="mb-0 fw-bold">
                <i class="bi bi-plus-circle me-2"></i>Nouvelle Période de Vacances
              </h5>
            </div>
            <div class="card-body p-4">
              <form method="POST" action="{{ route('jours_inactifs.store') }}" class="modern-form">
                @csrf
                <input type="hidden" name="active_tab" value="tab-vacance">

                <div class="mb-4">
                  <label class="form-label fw-semibold">
                    <i class="bi bi-tag me-1"></i>Titre de la période
                  </label>
                  <input type="text" name="titre" class="form-control form-control-lg rounded-3 border-2"
                    placeholder="Ex: Vacances d'été 2024" required>
                </div>

                <div class="row g-3 mb-4">
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <i class="bi bi-calendar-event me-1"></i>Date de début
                    </label>
                    <input type="date" name="date_debut" class="form-control form-control-lg rounded-3 border-2" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <i class="bi bi-calendar-check me-1"></i>Date de fin
                    </label>
                    <input type="date" name="date_fin" class="form-control form-control-lg rounded-3 border-2">
                  </div>
                </div>

                <button type="submit" class="btn btn-warning btn-lg rounded-pill w-100 shadow-sm">
                  <i class="bi bi-plus-lg me-2"></i>Ajouter la Période
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Liste des vacances -->
        <div class="col-lg-7">
          <div class="card border-0 shadow-lg rounded-4 h-100">
            <div class="card-header bg-gradient-warning text-dark border-0 rounded-top-4 py-3">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                  <i class="bi bi-list-ul me-2"></i>Périodes de Vacances
                </h5>
                <span class="badge bg-dark text-warning fs-6">{{ \App\Models\JourInactif::count() }} période(s)</span>
              </div>
            </div>
            <div class="card-body p-0">
              @php $vacances = \App\Models\JourInactif::all(); @endphp
              @if($vacances->count() > 0)
              <div class="table-responsive">
                <table class="table table-hover modern-table mb-0">
                  <thead class="table-light">
                    <tr>
                      <th class="px-4 py-3 fw-bold">Période</th>
                      <th class="px-4 py-3 fw-bold text-center d-none d-md-table-cell">Début</th>
                      <th class="px-4 py-3 fw-bold text-center d-none d-md-table-cell">Fin</th>
                      <th class="px-4 py-3 fw-bold text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($vacances as $vacance)
                    <tr class="table-row-hover">
                      <td class="px-4 py-3">
                        <div class="d-flex align-items-center">
                          <div class="icon-circle bg-warning me-3">
                            <i class="bi bi-calendar-x text-dark"></i>
                          </div>
                          <div>
                            <div class="fw-bold text-dark">{{ $vacance->titre }}</div>
                            <div class="d-md-none">
                              <small class="text-muted">
                                {{ \Carbon\Carbon::parse($vacance->date_debut)->format('d/m/Y') }}
                                @if($vacance->date_fin)
                                - {{ \Carbon\Carbon::parse($vacance->date_fin)->format('d/m/Y') }}
                                @endif
                              </small>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="px-4 py-3 text-center d-none d-md-table-cell">
                        <span class="badge bg-primary rounded-pill">
                          {{ \Carbon\Carbon::parse($vacance->date_debut)->format('d/m/Y') }}
                        </span>
                      </td>
                      <td class="px-4 py-3 text-center d-none d-md-table-cell">
                        @if($vacance->date_fin)
                        <span class="badge bg-success rounded-pill">
                          {{ \Carbon\Carbon::parse($vacance->date_fin)->format('d/m/Y') }}
                        </span>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                      </td>
                      <td class="px-4 py-3 text-center">
                        <div class="btn-group" role="group">
                          <a href="{{ route('jours_inactifs.edit', $vacance->id) }}"
                            class="btn btn-warning btn-sm rounded-pill me-1" title="Modifier">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <button type="button" class="btn btn-danger btn-sm rounded-pill"
                            onclick="openConfirmationModal('{{ route('jours_inactifs.destroy', $vacance->id) }}', 'Supprimer la période {{ $vacance->titre }} ?')"
                            title="Supprimer">
                            <i class="bi bi-trash"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              @else
              <div class="text-center py-5">
                <i class="bi bi-calendar-x display-4 text-muted mb-3"></i>
                <h5 class="text-muted">Aucune période de vacances</h5>
                <p class="text-muted">Définissez les périodes d'inactivité du système</p>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Confirmation -->
<div class="modal fade" id="modalConfirmation" tabindex="-1" aria-labelledby="modalConfirmationLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-gradient-danger text-white border-0 rounded-top-4">
        <h5 class="modal-title fw-bold" id="modalConfirmationLabel">
          <i class="bi bi-exclamation-triangle me-2"></i>Confirmation de Suppression
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="text-center mb-4">
          <div class="warning-icon mb-3">
            <i class="bi bi-exclamation-triangle text-warning display-3"></i>
          </div>
          <h5 id="modalMessage" class="mb-3">Êtes-vous sûr de vouloir supprimer cet élément ?</h5>
          <div class="alert alert-warning border-0 rounded-3">
            <i class="bi bi-info-circle me-2"></i>
            <small>Cette action est <strong>irréversible</strong> et supprimera définitivement l'élément sélectionné.</small>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 p-4 justify-content-center">
        <button type="button" class="btn btn-secondary btn-lg rounded-pill px-4 me-3" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-2"></i>Annuler
        </button>
        <form method="POST" id="modalForm" class="d-inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-lg rounded-pill px-4">
            <i class="bi bi-trash me-2"></i>Confirmer la suppression
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  /* Variables CSS */
  :root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
    --info-gradient: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
    --warning-gradient: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);
    --danger-gradient: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);
    --shadow-light: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    --shadow-medium: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    --shadow-heavy: 0 1rem 3rem rgba(0, 0, 0, 0.175);
  }

  /* Background gradients */
  .bg-gradient-config {
    background: var(--primary-gradient) !important;
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

  /* Statistics cards */
  .stat-card {
    transition: all 0.3s ease;
    cursor: pointer;
  }

  .stat-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: var(--shadow-heavy);
  }

  /* Navigation pills */
  .nav-pills-custom .nav-link {
    border: 2px solid transparent;
    font-weight: 600;
    transition: all 0.3s ease;
    padding: 0.75rem 1.5rem;
  }

  .nav-pills-custom .nav-link:not(.active) {
    background-color: #f8f9fa;
    color: #6c757d;
  }

  .nav-pills-custom .nav-link:not(.active):hover {
    background-color: #e9ecef;
    color: #495057;
    transform: translateY(-2px);
  }

  .nav-pills-custom .nav-link.active {
    background: var(--primary-gradient);
    color: white;
    box-shadow: var(--shadow-medium);
  }

  /* Modern table */
  .modern-table {
    font-size: 0.95rem;
  }

  .modern-table thead th {
    font-weight: 700;
    letter-spacing: 0.5px;
    border: none;
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

  /* Icon circles */
  .icon-circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    box-shadow: var(--shadow-light);
  }

  /* Modern forms */
  .modern-form .form-control,
  .modern-form .form-select {
    border-width: 2px;
    transition: all 0.3s ease;
    font-weight: 500;
  }

  .modern-form .form-control:focus,
  .modern-form .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    transform: translateY(-2px);
  }

  /* Buttons */
  .btn {
    font-weight: 600;
    letter-spacing: 0.3px;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-light);
  }

  .btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
  }

  .btn-lg {
    padding: 0.75rem 2rem;
    font-size: 1.1rem;
  }

  /* Cards */
  .card {
    transition: all 0.3s ease;
    border: none !important;
  }

  .card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-heavy) !important;
  }

  /* Badges */
  .badge {
    font-weight: 500;
    letter-spacing: 0.3px;
    box-shadow: var(--shadow-light);
  }

  .badge.fs-6 {
    font-size: 0.875rem !important;
    padding: 0.5rem 0.75rem !important;
  }

  /* Alerts */
  .alert {
    border: none;
    font-weight: 500;
    box-shadow: var(--shadow-light);
  }

  .alert-success {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
  }

  .alert-danger {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: #721c24;
  }

  /* Modal */
  .modal-content {
    border: none;
    overflow: hidden;
  }

  .modal-header {
    border-bottom: none;
    padding: 2rem;
  }

  .modal-body {
    padding: 2rem;
  }

  .modal-footer {
    border-top: none;
    padding: 2rem;
  }

  .warning-icon {
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0% {
      transform: scale(1);
    }

    50% {
      transform: scale(1.1);
    }

    100% {
      transform: scale(1);
    }
  }

  /* Responsive Design */
  @media (max-width: 1200px) {
    .modern-table {
      font-size: 0.9rem;
    }

    .icon-circle {
      width: 40px;
      height: 40px;
      font-size: 1rem;
    }
  }

  @media (max-width: 992px) {
    .stat-card .card-body {
      padding: 1rem !important;
    }

    .modern-table {
      font-size: 0.85rem;
    }

    .nav-pills-custom .nav-link {
      padding: 0.5rem 1rem;
      font-size: 0.9rem;
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

    .modern-table {
      font-size: 0.8rem;
    }

    .icon-circle {
      width: 35px;
      height: 35px;
      font-size: 0.9rem;
    }

    .btn-lg {
      padding: 0.6rem 1.5rem;
      font-size: 1rem;
    }

    .modal-header,
    .modal-body,
    .modal-footer {
      padding: 1.5rem;
    }
  }

  @media (max-width: 576px) {
    .nav-pills-custom {
      flex-wrap: nowrap;
      overflow-x: auto;
      padding-bottom: 0.5rem;
    }

    .nav-pills-custom .nav-link {
      white-space: nowrap;
      min-width: auto;
    }

    .stat-card .card-body h4 {
      font-size: 1.5rem;
    }

    .stat-card .card-body i {
      font-size: 1.5rem !important;
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

  /* Loading states */
  .btn:disabled {
    opacity: 0.6;
    transform: none !important;
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
  document.addEventListener("DOMContentLoaded", function() {
    // Gestion des onglets actifs
    const activeTab = "{{ session('active_tab', 'tab-filiere') }}";
    const trigger = document.querySelector(`a[href="#${activeTab}"]`);
    if (trigger) new bootstrap.Tab(trigger).show();

    // Animation des cartes statistiques
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
      card.style.animationDelay = `${index * 0.1}s`;
      card.classList.add('animate__animated', 'animate__fadeInUp');
    });

    // Tooltips pour les boutons
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Animation des formulaires
    const forms = document.querySelectorAll('.modern-form');
    forms.forEach(form => {
      form.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
          submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Traitement...';
          submitBtn.disabled = true;
        }
      });
    });
  });

  // Fonction pour ouvrir la modal de confirmation
  function openConfirmationModal(actionUrl, message) {
    const modal = new bootstrap.Modal(document.getElementById('modalConfirmation'));
    const form = document.getElementById('modalForm');
    const messageElement = document.getElementById('modalMessage');

    // Définir l'URL d'action du formulaire
    form.action = actionUrl;

    // Définir le message de confirmation
    messageElement.innerHTML = message;

    // Afficher la modal avec animation
    modal.show();
  }

  // Gestion de la fermeture de la modal avec Escape
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
      const modal = bootstrap.Modal.getInstance(document.getElementById('modalConfirmation'));
      if (modal) {
        modal.hide();
      }
    }
  });

  // Animation des boutons de suppression
  document.querySelectorAll('.btn-danger').forEach(button => {
    button.addEventListener('mouseenter', function() {
      this.style.transform = 'scale(1.05)';
    });

    button.addEventListener('mouseleave', function() {
      this.style.transform = 'scale(1)';
    });
  });

  // Confirmation avant soumission du formulaire de suppression
  document.getElementById('modalForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Suppression en cours...';
    submitBtn.disabled = true;
  });

  // Auto-save des formulaires (optionnel)
  const formInputs = document.querySelectorAll('.modern-form input, .modern-form textarea, .modern-form select');
  formInputs.forEach(input => {
    input.addEventListener('input', function() {
      // Sauvegarder temporairement dans localStorage
      localStorage.setItem(`form_${this.name}`, this.value);
    });
  });

  // Restaurer les valeurs des formulaires
  window.addEventListener('load', function() {
    formInputs.forEach(input => {
      const savedValue = localStorage.getItem(`form_${input.name}`);
      if (savedValue && !input.value) {
        input.value = savedValue;
      }
    });
  });

  // Nettoyer localStorage après soumission réussie
  document.querySelectorAll('.modern-form').forEach(form => {
    form.addEventListener('submit', function() {
      // Nettoyer les données sauvegardées
      const inputs = this.querySelectorAll('input, textarea, select');
      inputs.forEach(input => {
        localStorage.removeItem(`form_${input.name}`);
      });
    });
  });
</script>
@endpush