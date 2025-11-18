@extends('layouts.adminMenu')
@section('breadcrumb', 'Gestion des emplois du temps')
@section('title', 'Gestion des emplois du temps')

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
                <i class="bi bi-calendar3-event fs-2 text-white"></i>
              </div>
              <div>
                <h1 class="mb-0 fw-bold">Gestion des emplois du temps</h1>
                <p class="mb-0 opacity-75">Planifiez et gérez les séances de cours</p>
              </div>
            </div>
            <div class="d-none d-md-block">
              <div class="bg-white bg-opacity-10 rounded-3 p-3">
                <i class="bi bi-clock-history fs-1 text-white opacity-50"></i>
              </div>
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
      <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
        <div class="d-flex align-items-center">
          <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
            <i class="bi bi-check-circle-fill fs-4 text-success"></i>
          </div>
          <div>
            <strong>Succès!</strong> {{ session('success') }}
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
      </div>
    </div>
  </div>
  @endif

  <!-- Onglets -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-0">
          <ul class="nav nav-pills nav-pills-custom d-flex w-100 p-3 mb-0 gap-2" id="emploiTabs" role="tablist">
            <li class="flex-fill" role="presentation">
              <button class="nav-link active w-100" id="tab-calendrier" data-bs-toggle="tab" data-bs-target="#calendrier" type="button" role="tab">
                <i class="bi bi-calendar3 me-2"></i>Calendrier
              </button>
            </li>
            <li class="flex-fill" role="presentation">
              <button class="nav-link w-100" id="tab-ajout" data-bs-toggle="tab" data-bs-target="#ajout" type="button" role="tab">
                <i class="bi bi-plus-circle me-2"></i>Ajouter une séance
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="tab-content" id="emploiTabsContent">
    <!-- Onglet 1 : Calendrier -->
    <div class="tab-pane fade show active" id="calendrier" role="tabpanel" aria-labelledby="tab-calendrier">
      <!-- Calendar Section -->
      <div class="row">
        <div class="col-12">
          <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-light border-0 py-3">
              <div class="d-flex align-items-center">
                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                  <i class="bi bi-calendar3 text-info"></i>
                </div>
                <h5 class="mb-0 fw-bold text-info">Calendrier des séances</h5>
              </div>
            </div>
            <div class="card-body p-4">
              <div id="calendar" class="rounded-3 border"></div>
              <!-- Legend -->
              <div class="mt-4 d-flex flex-wrap justify-content-center gap-3">
                <div class="d-flex align-items-center">
                  <div class="legend-color bg-primary me-2"></div><span class="small text-muted">Séance programmée</span>
                </div>
                <div class="d-flex align-items-center">
                  <div class="legend-color bg-danger me-2"></div><span class="small text-muted">Jour inactif</span>
                </div>
                <div class="d-flex align-items-center">
                  <div class="legend-color bg-warning me-2"></div><span class="small text-muted">Jour férié</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row g-4 mb-4">
        <!-- Professor Filter -->
        <div class="col-12 col-lg-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-light border-0 py-3">
              <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                  <i class="bi bi-funnel text-primary"></i>
                </div>
                <h5 class="mb-0 fw-bold text-primary">Filtrer le calendrier</h5>
              </div>
            </div>
            <div class="card-body p-4">
              <label for="profFilter" class="form-label fw-semibold">
                <i class="bi bi-person-badge me-1 text-primary"></i>Filtrer par professeur
              </label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                  <i class="bi bi-person-badge"></i>
                </span>
                <select id="profFilter" class="form-select border-start-0">
                  <option value="">Tous les professeurs</option>
                  @foreach(\App\Models\Professeur::with('user')->get() as $prof)
                  <option value="{{ $prof->id }}">{{ $prof->user->name }} {{ $prof->user->prenom }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
        <!-- PDF Export -->
        <div class="col-12 col-lg-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-light border-0 py-3">
              <div class="d-flex align-items-center">
                <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3">
                  <i class="bi bi-filetype-pdf text-danger"></i>
                </div>
                <h5 class="mb-0 fw-bold text-danger">Export PDF</h5>
              </div>
            </div>
            <div class="card-body p-4">
              <form method="GET" action="{{ route('export.planning') }}" class="row g-3">
                <div class="col-12 col-md-5">
                  <label for="exportProf" class="form-label fw-semibold">Professeur</label>
                  <select name="professeur_id" class="form-select" id="exportProf">
                    <option value="">Tous</option>
                    @foreach(\App\Models\Professeur::with('user')->get() as $prof)
                    <option value="{{ $prof->id }}">{{ $prof->user->name }} {{ $prof->user->prenom }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-md-4">
                  <label for="exportGroupe" class="form-label fw-semibold">Groupe</label>
                  <select name="groupe_id" class="form-select" id="exportGroupe">
                    <option value="">Tous</option>
                    @foreach(\App\Models\Groupe::all() as $groupe)
                    <option value="{{ $groupe->id }}">{{ $groupe->nom_groupe }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-md-3 d-grid">
                  <label class="form-label">&nbsp;</label>
                  <button class="btn btn-danger h-100 rounded-pill" type="submit">
                    <i class="bi bi-download me-1"></i>Export
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Onglet 2 : Ajouter séance -->
    <div class="tab-pane fade" id="ajout" role="tabpanel" aria-labelledby="tab-ajout">
      <!-- Add Session Form -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-light border-0 py-3">
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                  <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                    <i class="bi bi-plus-circle text-primary"></i>
                  </div>
                  <h5 class="mb-0 fw-bold text-primary">Ajouter une séance</h5>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" data-bs-toggle="tooltip" title="Aide pour l'ajout de séances">
                  <i class="bi bi-question-circle me-1"></i>Aide
                </button>
              </div>
            </div>
            <div class="card-body p-4">
              <form method="POST" action="{{ url('/cours-sessions') }}" id="form-seance" class="needs-validation" novalidate>
                @csrf
                <!-- Section 1: Informations du cours -->
                <div class="section-divider mb-4">
                  <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                      <i class="bi bi-book text-primary"></i>
                    </div>
                    <h6 class="mb-0 fw-bold text-primary">Informations du cours</h6>
                  </div>
                </div>
                <div class="row g-3 mb-4">
                  <!-- Course Name -->
                  <div class="col-12 col-md-6 col-lg-4">
                    <label for="nomCours" class="form-label fw-semibold">
                      <i class="bi bi-book me-1 text-primary"></i>Nom du cours
                      <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-book"></i>
                      </span>
                      <input type="text" name="nom_cours" class="form-control border-start-0" id="nomCours" placeholder="Nom du cours" required data-bs-toggle="tooltip" title="Saisissez le nom complet du cours">
                      <div class="invalid-feedback">Veuillez saisir le nom du cours.</div>
                    </div>
                  </div>
                  <!-- Semester -->
                  <div class="col-12 col-md-6 col-lg-4">
                    <label for="semestre" class="form-label fw-semibold">
                      <i class="bi bi-calendar-range me-1 text-primary"></i>Semestre
                      <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-calendar-range"></i>
                      </span>
                      <select name="semestre" class="form-select border-start-0" id="semestre" required data-bs-toggle="tooltip" title="Sélectionnez le semestre concerné">
                        <option value="">Choisir un semestre...</option>
                        @foreach(['S1' => 'Semestre 1', 'S2' => 'Semestre 2', 'S3' => 'Semestre 3', 'S4' => 'Semestre 4', 'S5' => 'Semestre 5', 'S6' => 'Semestre 6'] as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                      </select>
                      <div class="invalid-feedback">Veuillez sélectionner un semestre.</div>
                    </div>
                  </div>
                  <!-- Group -->
                  <div class="col-12 col-md-6 col-lg-4">
                    <label for="groupe" class="form-label fw-semibold">
                      <i class="bi bi-people me-1 text-primary"></i>Groupe
                      <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-people"></i>
                      </span>
                      <select name="groupe_id" class="form-select border-start-0" id="groupe" required data-bs-toggle="tooltip" title="Sélectionnez le groupe d'étudiants">
                        <option value="">Choisir un groupe...</option>
                        @foreach(\App\Models\Filiere::with('groupes')->get() as $filiere)
                        <optgroup label="{{ $filiere->nom_filiere }}">
                          @foreach($filiere->groupes as $groupe)
                          <option value="{{ $groupe->id }}">{{ $groupe->nom_groupe }}</option>
                          @endforeach
                        </optgroup>
                        @endforeach
                      </select>
                      <div class="invalid-feedback">Veuillez sélectionner un groupe.</div>
                    </div>
                  </div>
                </div>

                <!-- Section 2: Ressources et personnel -->
                <div class="section-divider mb-4">
                  <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                      <i class="bi bi-person-workspace text-success"></i>
                    </div>
                    <h6 class="mb-0 fw-bold text-success">Ressources et personnel</h6>
                  </div>
                </div>
                <div class="row g-3 mb-4">
                  <!-- Professor -->
                  <div class="col-12 col-md-6">
                    <label for="professeur" class="form-label fw-semibold">
                      <i class="bi bi-person-badge me-1 text-success"></i>Professeur
                      <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-person-badge"></i>
                      </span>
                      <select name="id_professeur" class="form-select border-start-0" id="professeur" required data-bs-toggle="tooltip" title="Sélectionnez le professeur responsable">
                        <option value="">Choisir un professeur...</option>
                        @foreach(\App\Models\Professeur::with('user')->get() as $prof)
                        <option value="{{ $prof->id }}" data-email="{{ $prof->user->email ?? '' }}">
                          {{ $prof->user->name }} {{ $prof->user->prenom }}
                          @if($prof->specialite)- {{ $prof->specialite }}@endif
                        </option>
                        @endforeach
                      </select>
                      <div class="invalid-feedback">Veuillez sélectionner un professeur.</div>
                    </div>
                  </div>
                  <!-- Room -->
                  <div class="col-12 col-md-6">
                    <label for="salle" class="form-label fw-semibold">
                      <i class="bi bi-door-open me-1 text-success"></i>Salle
                      <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-door-open"></i>
                      </span>
                      <select name="nom_salle" class="form-select border-start-0" id="salle" required data-bs-toggle="tooltip" title="Sélectionnez la salle de cours">
                        <option value="">Choisir une salle...</option>
                        @foreach(\App\Models\Salle::all() as $salle)
                        <option value="{{ $salle->nom }}" data-capacite="{{ $salle->capacite ?? '' }}" data-type="{{ $salle->type ?? '' }}">
                          {{ $salle->nom }}
                          @if($salle->capacite)({{ $salle->capacite }} places)@endif
                          @if($salle->type)- {{ $salle->type }}@endif
                        </option>
                        @endforeach
                      </select>
                      <div class="invalid-feedback">Veuillez sélectionner une salle.</div>
                    </div>
                  </div>
                </div>

                <!-- Section 3: Planification temporelle -->
                <div class="section-divider mb-4">
                  <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                      <i class="bi bi-clock-history text-info"></i>
                    </div>
                    <h6 class="mb-0 fw-bold text-info">Planification temporelle</h6>
                  </div>
                </div>
                <div class="row g-3 mb-4">
                  <!-- Date -->
                  <div class="col-12 col-md-6 col-lg-3">
                    <label for="date" class="form-label fw-semibold">
                      <i class="bi bi-calendar-event me-1 text-info"></i>Date
                      <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-calendar-event"></i>
                      </span>
                      <input type="date" name="date" class="form-control border-start-0" id="date" required data-bs-toggle="tooltip" title="Sélectionnez la date de la séance">
                      <div class="invalid-feedback">Veuillez sélectionner une date.</div>
                    </div>
                  </div>
                  <!-- Start Time -->
                  <div class="col-12 col-md-6 col-lg-3">
                    <label for="heureDebut" class="form-label fw-semibold">
                      <i class="bi bi-clock me-1 text-info"></i>Heure début
                      <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-clock"></i>
                      </span>
                      <input type="time" name="heure_debut" class="form-control border-start-0" id="heureDebut" required min="07:00" max="21:00" data-bs-toggle="tooltip" title="Heure de début de la séance">
                      <div class="invalid-feedback">Veuillez saisir l'heure de début.</div>
                    </div>
                  </div>
                  <!-- End Time -->
                  <div class="col-12 col-md-6 col-lg-3">
                    <label for="heureFin" class="form-label fw-semibold">
                      <i class="bi bi-clock-fill me-1 text-info"></i>Heure fin
                      <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-clock-fill"></i>
                      </span>
                      <input type="time" name="heure_fin" class="form-control border-start-0" id="heureFin" required min="07:00" max="22:00" data-bs-toggle="tooltip" title="Heure de fin de la séance">
                      <div class="invalid-feedback">Veuillez saisir l'heure de fin.</div>
                    </div>
                  </div>
                  <!-- Duration Display -->
                  <div class="col-12 col-md-6 col-lg-3">
                    <label for="duree" class="form-label fw-semibold">
                      <i class="bi bi-hourglass-split me-1 text-info"></i>Durée
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-hourglass-split"></i>
                      </span>
                      <input type="text" class="form-control border-start-0 bg-light" id="duree" readonly placeholder="Durée calculée">
                    </div>
                  </div>
                </div>

                <!-- Section 4: Options avancées -->
                <div class="section-divider mb-4">
                  <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                      <i class="bi bi-gear text-warning"></i>
                    </div>
                    <h6 class="mb-0 fw-bold text-warning">Options avancées</h6>
                  </div>
                </div>
                <div class="row g-3 mb-4">
                  <!-- Repeat Weeks -->
                  <div class="col-12 col-md-6 col-lg-4">
                    <label for="repeatWeeks" class="form-label fw-semibold">
                      <i class="bi bi-arrow-repeat me-1 text-warning"></i>Répéter (semaines)
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-arrow-repeat"></i>
                      </span>
                      <input type="number" name="repeat_weeks" class="form-control border-start-0" id="repeatWeeks" min="0" max="20" value="0" data-bs-toggle="tooltip" title="Nombre de semaines à répéter (0 = pas de répétition)">
                    </div>
                    <div class="form-text">
                      <small class="text-muted">0 = séance unique, 1-20 = répétition hebdomadaire</small>
                    </div>
                  </div>
                </div>

                <!-- Repeat Preview -->
                <div id="repeatPreview" class="mb-4" style="display: none;">
                  <div class="alert alert-info border-0 rounded-3 shadow-sm">
                    <h6 class="alert-heading fw-bold text-info"><i class="bi bi-info-circle me-2"></i>Aperçu de la répétition</h6>
                    <div id="repeatDates" class="text-muted small"></div>
                  </div>
                </div>

                <!-- Submit Section -->
                <div class="border-top pt-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
                  <div class="d-flex align-items-center text-muted mb-3 mb-md-0">
                    <i class="bi bi-info-circle me-2"></i><small>Les champs marqués d'un * sont obligatoires</small>
                  </div>
                  <div class="d-grid d-md-flex gap-2">
                    <button type="reset" class="btn btn-outline-secondary rounded-pill px-4">
                      <i class="bi bi-arrow-clockwise me-1"></i>Réinitialiser
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow">
                      <i class="bi bi-plus-circle me-2"></i>Ajouter la séance
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Event Details Modal -->
<div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-gradient-primary text-white rounded-top-4">
        <h5 class="modal-title fw-bold" id="modal-title"><i class="bi bi-info-circle me-2"></i>Détails de la séance</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body p-4" id="modal-body">
        <!-- Content injected by JavaScript -->
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-gradient-danger text-white rounded-top-4">
        <h5 class="modal-title fw-bold" id="confirmDeleteLabel"><i class="bi bi-exclamation-triangle me-2"></i>Confirmation</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body p-4">
        <div class="text-center">
          <i class="bi bi-exclamation-triangle-fill text-warning display-4 mb-3"></i>
          <p id="confirmDeleteMessage" class="lead">Voulez-vous vraiment supprimer cet élément ?</p>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i>Annuler
        </button>
        <button id="confirmDeleteBtn" class="btn btn-danger rounded-pill px-4">
          <i class="bi bi-trash me-1"></i>Supprimer
        </button>
      </div>
    </div>
  </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
          // Focus on first invalid field
          const firstInvalid = form.querySelector(':invalid');
          if (firstInvalid) {
            firstInvalid.focus();
            firstInvalid.scrollIntoView({
              behavior: 'smooth',
              block: 'center'
            });
          }
        }
        form.classList.add('was-validated');
      });
    });

    // Real-time validation for required fields
    const formSeance = document.getElementById('form-seance');
    const requiredFields = formSeance.querySelectorAll('[required]');
    requiredFields.forEach(field => {
      field.addEventListener('blur', function() {
        if (this.checkValidity()) {
          this.classList.remove('is-invalid');
          this.classList.add('is-valid');
        } else {
          this.classList.remove('is-valid');
          this.classList.add('is-invalid');
        }
      });
    });

    // Calculate duration
    const heureDebut = document.getElementById('heureDebut');
    const heureFin = document.getElementById('heureFin');
    const dureeField = document.getElementById('duree');

    function calculateDuration() {
      if (heureDebut.value && heureFin.value) {
        const debut = new Date(`2000-01-01T${heureDebut.value}`);
        const fin = new Date(`2000-01-01T${heureFin.value}`);
        if (fin > debut) {
          const diff = fin - debut;
          const hours = Math.floor(diff / (1000 * 60 * 60));
          const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
          dureeField.value = `${hours}h${minutes.toString().padStart(2, '0')}`;
          heureFin.classList.remove('is-invalid');
        } else {
          dureeField.value = '';
          heureFin.classList.add('is-invalid');
          heureFin.nextElementSibling.nextElementSibling.textContent = 'L\'heure de fin doit être après l\'heure de début.';
        }
      } else {
        dureeField.value = '';
      }
    }

    heureDebut.addEventListener('change', calculateDuration);
    heureFin.addEventListener('change', calculateDuration);

    // Auto-suggest end time (add 1.5 hours to start time)
    heureDebut.addEventListener('change', function() {
      if (this.value && !heureFin.value) {
        const debut = new Date(`2000-01-01T${this.value}`);
        debut.setMinutes(debut.getMinutes() + 90); // Add 1.5 hours
        const hours = debut.getHours().toString().padStart(2, '0');
        const minutes = debut.getMinutes().toString().padStart(2, '0');
        heureFin.value = `${hours}:${minutes}`;
        calculateDuration();
      }
    });

    // Show repeat preview
    const repeatWeeks = document.getElementById('repeatWeeks');
    const dateField = document.getElementById('date');
    const repeatPreview = document.getElementById('repeatPreview');
    const repeatDates = document.getElementById('repeatDates');

    function showRepeatPreview() {
      const weeks = parseInt(repeatWeeks.value) || 0;
      const selectedDate = dateField.value;
      if (weeks > 0 && selectedDate) {
        const dates = [];
        const startDate = new Date(selectedDate);
        for (let i = 0; i <= weeks; i++) {
          const currentDate = new Date(startDate);
          currentDate.setDate(startDate.getDate() + (i * 7));
          dates.push(currentDate.toLocaleDateString('fr-FR', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
          }));
        }
        repeatDates.innerHTML = `
                    <strong>Séances programmées :</strong><br>${dates.map(date => `• ${date}`).join('<br>')}
                    <br><br>
                    <small class="text-muted">Total : ${dates.length} séance(s)</small>`;
        repeatPreview.style.display = 'block';
      } else {
        repeatPreview.style.display = 'none';
      }
    }

    repeatWeeks.addEventListener('input', showRepeatPreview);
    dateField.addEventListener('change', showRepeatPreview);

    // Calendar setup
    const calendarEl = document.getElementById('calendar');
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const profFilter = document.getElementById('profFilter');
    const joursFeries = {
      '01-01': 'Nouvel An',
      '01-11': "Manifeste de l'Indépendance",
      '01-14': 'Nouvel An AMAZIGH',
      '05-01': 'Fête du Travail',
      '07-30': 'Fête du Trône',
      '08-14': 'Oued Ed-Dahab',
      '08-20': 'Révolution',
      '08-21': 'Fête de la Jeunesse',
      '11-06': 'Marche Verte',
      '11-18': "Fête de l'Indépendance"
    };

    const calendar = new FullCalendar.Calendar(calendarEl, {
      locale: 'fr',
      initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
      height: 'auto',
      selectable: true,
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: window.innerWidth < 768 ? 'listWeek,dayGridMonth' : 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      buttonText: {
        today: "Aujourd'hui",
        month: 'Mois',
        week: 'Semaine',
        day: 'Jour',
        list: 'Liste'
      },
      selectAllow: function(selectInfo) {
        const d = new Date(selectInfo.startStr);
        const jour = d.getDay();
        const key = String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
        return jour !== 0 && !joursFeries[key];
      },
      dayCellDidMount: function(info) {
        const key = String(info.date.getMonth() + 1).padStart(2, '0') + '-' + String(info.date.getDate()).padStart(2, '0');
        const name = joursFeries[key];
        if (name) {
          info.el.classList.add('fc-jour-ferie');
          info.el.setAttribute('data-ferie-name', name);
          info.el.style.backgroundColor = '#fff3cd'; // Light yellow for holidays
        }
      },
      events: async function(fetchInfo, successCallback, failureCallback) {
        try {
          const profId = profFilter.value;
          const urlSessions = profId ? `/cours-sessions-professeur/${profId}` : '/cours-sessions-api';
          const urlVacances = '/jours-inactifs-api';
          const [sessions, joursInactifs] = await Promise.all([
            fetch(urlSessions).then(res => res.json()),
            fetch(urlVacances).then(res => res.json())
          ]);

          const vacancesAvecType = joursInactifs.map(j => ({
            ...j,
            type: 'vacance',
            backgroundColor: '#dc3545', // Danger red for inactive days
            borderColor: '#dc3545'
          }));

          successCallback([...sessions, ...vacancesAvecType]);
        } catch (error) {
          console.error('Erreur lors du chargement des événements:', error);
          failureCallback(error);
        }
      },
      eventClick: function(info) {
        const event = info.event;
        const props = event.extendedProps;
        const isVacance = props.type === 'vacance';
        const modal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));

        document.getElementById('modal-title').innerHTML = `
                    <i class="bi bi-${isVacance ? 'calendar-x' : 'calendar-check'} me-2"></i>${event.title}`;
        document.getElementById('modal-body').innerHTML = `
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="card bg-light border-0 shadow-sm h-100">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-primary mb-2">
                                        <i class="bi bi-calendar-event me-1"></i>Informations temporelles
                                    </h6>
                                    <p class="mb-1"><strong>Date :</strong> ${new Date(event.startStr).toLocaleDateString('fr-FR')}</p>
                                    <p class="mb-0"><strong>Heure :</strong> ${event.startStr.substring(11, 16) || '-'} → ${event.endStr?.substring(11, 16) || '-'}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card bg-light border-0 shadow-sm h-100">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-success mb-2">
                                        <i class="bi bi-people me-1"></i>Détails académiques
                                    </h6>
                                    <p class="mb-1"><strong>Professeur :</strong> ${props.professeur || '-'}</p>
                                    <p class="mb-1"><strong>Salle :</strong> ${props.salle || '-'}</p>
                                    <p class="mb-1"><strong>Groupe :</strong> ${props.groupe || '-'}</p>
                                    <p class="mb-0"><strong>Filière :</strong> ${props.filiere || '-'}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <a href="${isVacance ? `/jours_inactifs/${event.id}/edit` : `/cours-sessions/${event.id}/edit`}"
                            class="btn btn-warning rounded-pill px-4">
                            <i class="bi bi-pencil me-1"></i>Modifier
                        </a>
                        <button class="btn btn-danger rounded-pill px-4"
                                 onclick="${isVacance ? `deleteVacance(${event.id}, '${csrf}')` : `deleteEvent(${event.id}, '${csrf}')`}">
                            <i class="bi bi-trash me-1"></i>Supprimer
                        </button>
                    </div>`;
        modal.show();
      }
    });
    calendar.render();

    // Responsive calendar
    window.addEventListener('resize', function() {
      if (window.innerWidth < 768) {
        calendar.changeView('listWeek');
      } else {
        calendar.changeView('dayGridMonth');
      }
    });

    profFilter.addEventListener('change', () => {
      calendar.refetchEvents();
    });
  });

  let deleteCallback = null;

  function showDeleteModal(message, onConfirm) {
    document.getElementById('confirmDeleteMessage').textContent = message;
    deleteCallback = onConfirm;
    const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    modal.show();
  }

  function deleteEvent(id, csrf) {
    showDeleteModal("Voulez-vous vraiment supprimer cette séance ?", () => {
      fetch(`/cours-sessions/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': csrf
        }
      }).then(res => {
        if (!res.ok) throw new Error('Erreur de suppression');
        location.reload();
      }).catch(err => {
        console.error('Erreur:', err);
        alert("Erreur lors de la suppression: " + err.message);
      });
    });
  }

  function deleteVacance(id, csrf) {
    showDeleteModal("Voulez-vous vraiment supprimer cette vacance ?", () => {
      fetch(`/jours_inactifs/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': csrf
        }
      }).then(res => {
        if (!res.ok) throw new Error('Erreur de suppression');
        location.reload();
      }).catch(err => {
        console.error('Erreur:', err);
        alert("Erreur lors de la suppression: " + err.message);
      });
    });
  }

  document.getElementById('confirmDeleteBtn')?.addEventListener('click', () => {
    if (deleteCallback) deleteCallback();
    const modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
    modal?.hide();
  });
</script>
@endsection

@push('styles')
<style>
  /* Custom gradient */
  .bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  }

  .bg-gradient-danger {
    background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%) !important;
  }

  /* Section dividers */
  .section-divider {
    position: relative;
    padding-left: 1rem;
    border-left: 4px solid transparent;
  }

  .section-divider:nth-of-type(1) {
    border-left-color: #667eea;
    /* Primary color for course info */
  }

  .section-divider:nth-of-type(2) {
    border-left-color: #198754;
    /* Success color for resources */
  }

  .section-divider:nth-of-type(3) {
    border-left-color: #0dcaf0;
    /* Info color for time planning */
  }

  .section-divider:nth-of-type(4) {
    border-left-color: #ffc107;
    /* Warning color for advanced options */
  }

  /* Form enhancements */
  .form-control:focus,
  .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    transform: translateY(-1px);
    transition: all 0.3s ease;
  }

  .input-group-text {
    transition: all 0.3s ease;
  }

  .form-control:focus+.input-group-text,
  .form-select:focus+.input-group-text {
    border-color: #86b7fe;
    background-color: rgba(13, 110, 253, 0.1);
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

  /* Card enhancements */
  .card {
    transition: all 0.3s ease;
    border-radius: 1rem;
  }

  .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
  }

  /* Alert enhancements */
  .alert {
    border-radius: 1rem;
    border: none;
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

  /* FullCalendar specific styles */
  #calendar {
    min-height: 500px;
    border: 1px solid #e9ecef;
    border-radius: 0.75rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  }

  .fc-event {
    border: none !important;
    border-radius: 6px !important;
    padding: 2px 6px !important;
    font-weight: 500 !important;
    font-size: 0.85rem;
    background-color: #667eea !important;
    /* Primary color for events */
    color: white !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
  }

  .fc-event:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
  }

  .fc-event.vacance {
    background-color: #dc3545 !important;
    /* Danger red for inactive days */
    color: white !important;
  }

  .fc-jour-ferie {
    background-color: #fff3cd !important;
    /* Light yellow for holidays */
    position: relative;
  }

  .fc-jour-ferie::after {
    content: attr(data-ferie-name);
    position: absolute;
    bottom: 2px;
    left: 2px;
    font-size: 0.7rem;
    color: #856404;
    font-weight: bold;
  }

  /* Calendar toolbar buttons */
  .fc-button {
    font-size: 0.85rem !important;
    padding: 0.375rem 0.75rem !important;
    border-radius: 0.5rem !important;
    transition: all 0.2s ease;
  }

  .fc-button:hover {
    transform: translateY(-1px);
  }

  /* Legend styling */
  .legend-color {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    display: inline-block;
    vertical-align: middle;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  }

  /* Responsive improvements */
  @media (max-width: 768px) {
    .card-body {
      padding: 1.5rem !important;
    }

    .section-divider {
      border-left: none;
      border-top: 4px solid;
      padding-left: 0;
      padding-top: 0.5rem;
      margin-bottom: 1rem;
    }

    .avatar-circle {
      width: 50px;
      height: 50px;
      font-size: 1rem;
    }

    .fc-toolbar {
      flex-direction: column;
      gap: 0.5rem;
    }

    .fc-toolbar-chunk {
      display: flex;
      justify-content: center;
    }

    .fc-toolbar-title {
      font-size: 1.1rem !important;
      margin: 0.5rem 0;
    }

    .fc-button {
      font-size: 0.75rem !important;
      padding: 0.25rem 0.5rem !important;
    }

    #calendar {
      font-size: 0.8rem;
      min-height: 400px;
    }
  }

  @media (max-width: 576px) {
    .container-fluid {
      padding-left: 1rem;
      padding-right: 1rem;
    }

    .card-body {
      padding: 1rem !important;
    }

    .btn-lg {
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
    }

    .legend-color {
      width: 16px;
      height: 16px;
    }

    .small {
      font-size: 0.8rem !important;
    }
  }

  /* Loading state for submit button */
  .btn:disabled {
    position: relative;
    color: transparent !important;
  }

  .btn:disabled::after {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    top: 50%;
    left: 50%;
    margin-left: -8px;
    margin-top: -8px;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }

  /* Preview section styling */
  #repeatPreview {
    animation: fadeIn 0.3s ease-in-out;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Smooth card entrance animation */
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
</style>
@endpush