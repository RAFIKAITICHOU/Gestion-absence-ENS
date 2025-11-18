@extends('layouts.profMenu')
@section('breadcrumb', 'Gérer la séance')
@section('title', 'Gérer la séance')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        --warning-gradient: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        --hover-transform: translateY(-2px);
    }

    .custom-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        border: none;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .main-card:hover {
        transform: var(--hover-transform);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .card-header-custom {
        background: var(--primary-gradient);
        color: white;
        padding: 25px;
        border: none;
    }

    .card-header-custom h4 {
        margin: 0;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .info-section {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border-radius: 15px;
        padding: 20px;
        margin: 20px 0;
        border-left: 5px solid #2196f3;
    }

    .btn-custom {
        border-radius: 25px;
        padding: 10px 25px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .btn-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-custom:hover::before {
        left: 100%;
    }

    .btn-pdf {
        background: var(--danger-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(250, 112, 154, 0.4);
    }

    .btn-pdf:hover {
        transform: var(--hover-transform);
        box-shadow: 0 6px 20px rgba(250, 112, 154, 0.6);
        color: white;
    }

    .btn-save {
        background: var(--success-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);
    }

    .btn-save:hover {
        transform: var(--hover-transform);
        box-shadow: 0 6px 20px rgba(79, 172, 254, 0.6);
        color: white;
    }

    .table-custom {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: none;
    }

    .table-custom thead {
        background: var(--primary-gradient);
        color: white;
    }

    .table-custom thead th {
        border: none;
        padding: 15px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-custom tbody tr {
        transition: all 0.3s ease;
        border: none;
    }

    .table-custom tbody tr:hover {
        background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%);
        transform: scale(1.01);
    }

    .table-custom tbody td {
        padding: 15px;
        border: none;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .btn-toggle {
        border-radius: 20px;
        padding: 8px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        min-width: 100px;
    }

    .btn-present {
        background: linear-gradient(135deg, #4caf50 0%, #8bc34a 100%);
        color: white;
        box-shadow: 0 3px 10px rgba(76, 175, 80, 0.3);
    }

    .btn-absent {
        background: linear-gradient(135deg, #f44336 0%, #ff9800 100%);
        color: white;
        box-shadow: 0 3px 10px rgba(244, 67, 54, 0.3);
    }

    .btn-toggle:hover {
        transform: var(--hover-transform);
    }

    .form-control-custom {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }

    .form-control-custom:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        transform: scale(1.02);
    }

    .alert-custom {
        border-radius: 15px;
        border: none;
        padding: 15px 20px;
        margin: 20px 0;
    }

    .alert-success-custom {
        background: var(--success-gradient);
        color: white;
    }

    .alert-danger-custom {
        background: var(--danger-gradient);
        color: white;
    }

    .alert-warning-custom {
        background: var(--warning-gradient);
        color: #8b4513;
    }

    .student-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1.1em;
    }

    .time-display {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        display: inline-block;
        font-weight: 600;
        margin-top: 10px;
    }

    .justification-section {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-file {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        border: none;
        border-radius: 15px;
        padding: 5px 10px;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }

    .btn-file:hover {
        transform: var(--hover-transform);
        color: white;
        box-shadow: 0 3px 10px rgba(23, 162, 184, 0.4);
    }

    @media (max-width: 768px) {
        .custom-container {
            padding: 10px;
        }

        .card-header-custom {
            padding: 20px 15px;
        }

        .card-header-custom h4 {
            font-size: 1.3rem;
        }

        .info-section {
            padding: 15px;
            margin: 15px 0;
        }

        .table-responsive {
            border-radius: 15px;
        }

        .btn-custom {
            padding: 8px 15px;
            font-size: 0.9rem;
        }

        .btn-toggle {
            min-width: 80px;
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        .form-control-custom {
            padding: 8px 12px;
        }

        .justification-section {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 576px) {
        .table-custom thead th {
            padding: 10px 5px;
            font-size: 0.8rem;
        }

        .table-custom tbody td {
            padding: 10px 5px;
        }

        .student-name {
            font-size: 0.9rem;
        }

        .btn-toggle {
            min-width: 70px;
            padding: 5px 10px;
            font-size: 0.7rem;
        }
    }

    .fade-in {
        animation: fadeIn 0.6s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .search-container {
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
    }

    .search-input {
        padding-left: 2.75rem;
        font-size: 1rem;
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 2px solid #dee2e6;
    }

    .search-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background-color: #fff;
    }
</style>

<div class="custom-container">
    <div class="container-fluid">
        @if(session('success'))
        <div class="alert alert-success-custom alert-dismissible fade show fade-in" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger-custom alert-dismissible fade show fade-in" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
        @endif

        <div class="card main-card fade-in">
            <div class="card-header-custom">
                <h4 class="mb-0">
                    <i class="bi bi-clipboard-check me-2"></i>
                    Gérer la présence – {{ $seance->cours->nom }} ({{ $seance->groupe->nom_groupe }})
                </h4>
            </div>

            @if($seanceFuture)
            <div class="alert alert-warning-custom mt-3 fade-in">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Attention :</strong> Vous ne pouvez pas saisir les présences avant le début de la séance.
            </div>
            @endif

            <div class="card-body p-4">
                <div class="info-section">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <strong><i class="bi bi-calendar3 me-2"></i>Date :</strong>
                            {{ \Carbon\Carbon::parse($seance->date)->translatedFormat('l d F Y') }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="bi bi-clock me-2"></i>Heure :</strong>
                            de {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }}
                            à {{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="{{ route('presence.export', $seance->id) }}" target="_blank"
                        class="btn btn-custom btn-pdf">
                        <i class="bi bi-file-earmark-pdf-fill me-2"></i>Télécharger PDF
                    </a>
                </div>

                <form method="POST" action="{{ route('presence.enregistrer') }}">
                    @csrf
                    <input type="hidden" name="seance_id" value="{{ $seance->id }}">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="marquerTousAbsents()">
                                <i class="bi bi-x-circle me-1"></i> Tous absents
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="marquerTousPresents()">
                                <i class="bi bi-check-circle me-1"></i> Tous présents
                            </button>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="filterRows('absent')">
                                <i class="bi bi-x-circle me-1"></i> Afficher les absents
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="filterRows('present')">
                                <i class="bi bi-check-circle me-1"></i> Afficher les présents
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="filterRows('all')">
                                <i class="bi bi-list-ul me-1"></i> Tout afficher
                            </button>
                        </div>
                    </div>

                    @php
                    $presences = $seance->presences->keyBy('id_etudiant');
                    @endphp

                    <div class="search-container mb-4">
                        <input type="text" id="searchInput" class="form-control form-control-custom search-input"
                            placeholder="🔍 Rechercher un étudiant (nom ou prénom)...">
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr class="text-center">
                                    <th><i class="bi bi-person me-1"></i>Étudiant</th>
                                    <th><i class="bi bi-check-circle me-1"></i>État</th>
                                    <th><i class="bi bi-clock-history me-1"></i>Retard</th>
                                    <th><i class="bi bi-chat-text me-1"></i>Remarque</th>
                                    <th><i class="bi bi-star me-1"></i>Bonus</th>
                                    <th><i class="bi bi-file-earmark-text me-1"></i>Justification</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($seance->groupe->etudiants as $etudiant)
                                @php
                                $presence = $presences[$etudiant->id] ?? null;
                                $etat = $presence?->etat ?? 1;
                                $remarque = $presence?->remarque ?? '';
                                $bonus = $presence?->bonus ?? 0;
                                $retard = $presence?->retard ?? 0;
                                @endphp
                                <tr class="fade-in {{ $etat == 1 ? 'row-present' : 'row-absent' }}">
                                    <td class="student-name">
                                        <i class="bi bi-person-circle me-2 text-primary"></i>
                                        {{ $etudiant->user->prenom }} {{ $etudiant->user->name }}
                                    </td>
                                    <td class="text-center">
                                        <input type="hidden" name="absences[{{ $etudiant->id }}][etat]"
                                            value="{{ $etat }}" id="etat-input-{{ $etudiant->id }}">
                                        <button type="button"
                                            class="btn btn-toggle {{ $etat == 1 ? 'btn-present' : 'btn-absent' }}"
                                            data-etudiant="{{ $etudiant->id }}"
                                            {{ $seanceFuture ? 'disabled' : '' }}>
                                            <i class="bi {{ $etat == 1 ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                            <span>{{ $etat == 1 ? 'Présent' : 'Absent' }}</span>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <input type="number"
                                            name="absences[{{ $etudiant->id }}][retard]"
                                            class="form-control form-control-custom text-center"
                                            placeholder="en min"
                                            min="0"
                                            value="{{ $presence?->retard }}"
                                            {{ $seanceFuture ? 'disabled' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="absences[{{ $etudiant->id }}][remarque]"
                                            class="form-control form-control-custom"
                                            placeholder="Remarque éventuelle..."
                                            value="{{ $remarque }}" {{ $seanceFuture ? 'disabled' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="absences[{{ $etudiant->id }}][bonus]"
                                            class="form-control form-control-custom text-center" step="0.5"
                                            placeholder="0"
                                            value="{{ $bonus }}" {{ $seanceFuture ? 'disabled' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        @if($etat == 0)
                                        <div class="justification-section">
                                            @if(!empty($presence?->justification))
                                            <span class="badge bg-success">Justifiée</span>
                                            @if($presence?->justificatif_fichier)
                                            <button type="button" class="btn btn-file btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewFileModal{{ $presence->id }}">
                                                <i class="bi bi-eye me-1"></i>Voir fichier
                                            </button>
                                            @endif
                                            @else
                                            <span class="badge bg-danger">Non justifiée</span>
                                            @endif
                                        </div>
                                        @else
                                        —
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
                        <div class="time mb-3 mb-md-0">
                            <i class="bi bi-clock me-2"></i>{{ now()->format('d/m/Y H:i:s') }}
                        </div>
                        <button type="submit" class="btn btn-custom btn-save {{ $seanceFuture ? 'pulse' : '' }}"
                            {{ $seanceFuture ? 'disabled' : '' }}>
                            <i class="bi bi-check-circle me-2"></i>Enregistrer les présences
                        </button>
                    </div>

                    @if($seanceFuture)
                    <div class="alert alert-warning-custom mt-3 fade-in">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Attention :</strong> Vous ne pouvez pas saisir les présences avant le début de la séance.
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modals pour visualisation des fichiers de justification -->
@foreach($seance->groupe->etudiants as $etudiant)
@php
$presence = $presences[$etudiant->id] ?? null;
@endphp
@if($presence && $presence->justification && $presence->justificatif_fichier)
<!-- Modal de visualisation du fichier -->
<div class="modal fade" id="viewFileModal{{ $presence->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Fichier de justification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Étudiant:</strong> {{ $etudiant->user->prenom }} {{ $etudiant->user->name }}
                </div>
                <div class="mb-3">
                    <strong>Cours:</strong> {{ $seance->cours->nom }}
                </div>
                <div class="mb-3">
                    <strong>Date:</strong> {{ $seance->date }}
                </div>
                <div class="mb-3">
                    <strong>Justification:</strong> {{ $presence->justification }}
                </div>
                <div class="text-center">
                    @php
                    $extension = pathinfo($presence->justificatif_fichier, PATHINFO_EXTENSION);
                    @endphp

                    @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                    <img src="{{ asset('storage/' . $presence->justificatif_fichier) }}"
                        class="img-fluid" style="max-height: 400px;" alt="Justificatif">
                    @elseif(strtolower($extension) === 'pdf')
                    <iframe src="{{ asset('storage/' . $presence->justificatif_fichier) }}"
                        width="100%" height="400px" frameborder="0">
                        <p>Votre navigateur ne supporte pas l'affichage des PDF.
                            <a href="{{ route('presences.fichier.telecharger.prof', $presence->id) }}">Télécharger le fichier</a>
                        </p>
                    </iframe>
                    @else
                    <div class="alert alert-info">
                        <i class="bi bi-file-earmark-text fs-1"></i>
                        <p>Fichier: {{ basename($presence->justificatif_fichier) }}</p>
                        <p>Ce type de fichier ne peut pas être prévisualisé.</p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('presences.fichier.telecharger.prof', $presence->id) }}"
                    class="btn btn-primary">
                    <i class="bi bi-download"></i> Télécharger
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Observer animation des lignes
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

        document.querySelectorAll('tbody tr').forEach(row => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            row.style.transition = 'all 0.6s ease';
            observer.observe(row);
        });

        // Toggle Présent / Absent
        document.querySelectorAll('.btn-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const etudiantId = this.getAttribute('data-etudiant');
                const input = document.getElementById(`etat-input-${etudiantId}`);
                const icon = this.querySelector('i');
                const label = this.querySelector('span');
                const current = input.value;

                // Animation visuelle
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);

                if (current === "1") {
                    input.value = "0";
                    this.classList.remove('btn-present');
                    this.classList.add('btn-absent');
                    icon.classList.remove('bi-check-circle');
                    icon.classList.add('bi-x-circle');
                    label.innerText = 'Absent';
                } else {
                    input.value = "1";
                    this.classList.remove('btn-absent');
                    this.classList.add('btn-present');
                    icon.classList.remove('bi-x-circle');
                    icon.classList.add('bi-check-circle');
                    label.innerText = 'Présent';
                }
            });
        });

        // Animation champs de saisie
        document.querySelectorAll('.form-control-custom').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Hover sur lignes du tableau
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
            });

            row.addEventListener('mouseleave', function() {
                this.style.boxShadow = 'none';
            });
        });
    });

    // Fonction de filtrage par état
    window.filterRows = function(type) {
        document.querySelectorAll('tbody tr').forEach(row => {
            if (type === 'all') {
                row.style.display = '';
            } else if (type === 'absent') {
                row.style.display = row.classList.contains('row-absent') ? '' : 'none';
            } else if (type === 'present') {
                row.style.display = row.classList.contains('row-present') ? '' : 'none';
            }
        });
    };

    // Marquer tous les étudiants comme absents
    window.marquerTousAbsents = function() {
        document.querySelectorAll('.btn-toggle').forEach(button => {
            const etudiantId = button.getAttribute('data-etudiant');
            const input = document.getElementById(`etat-input-${etudiantId}`);
            const icon = button.querySelector('i');
            const label = button.querySelector('span');

            input.value = "0";
            button.classList.remove('btn-present');
            button.classList.add('btn-absent');
            icon.classList.remove('bi-check-circle');
            icon.classList.add('bi-x-circle');
            label.innerText = 'Absent';
        });
    };

    // Marquer tous les étudiants comme présents
    window.marquerTousPresents = function() {
        document.querySelectorAll('.btn-toggle').forEach(button => {
            const etudiantId = button.getAttribute('data-etudiant');
            const input = document.getElementById(`etat-input-${etudiantId}`);
            const icon = button.querySelector('i');
            const label = button.querySelector('span');

            input.value = "1";
            button.classList.remove('btn-absent');
            button.classList.add('btn-present');
            icon.classList.remove('bi-x-circle');
            icon.classList.add('bi-check-circle');
            label.innerText = 'Présent';
        });
    };

    // Recherche dynamique par nom ou prénom
    document.getElementById('searchInput').addEventListener('input', function() {
        const value = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            const name = row.querySelector('.student-name').textContent.toLowerCase();
            row.style.display = name.includes(value) ? '' : 'none';
        });
    });
</script>
@endsection