@extends('layouts.' . $menu)

@section('breadcrumb', 'Mon Emploi du Temps')
@section('title', 'Mon Emploi du Temps')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 bg-primary-gradient text-white card-animated" data-delay="0.1"> {{-- Changed bg-gradient-primary to bg-primary-gradient and added card-animated --}}
                <div class="card-body py-4 px-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                                <i class="fas fa-calendar-alt fs-2 text-white"></i> {{-- Changed icon to fas fa-calendar-alt --}}
                            </div>
                            <div>
                                <h1 class="mb-1 fw-bold">Mon emploi du temps</h1>
                                <p class="mb-0 opacity-75">
                                    Bienvenue, <strong>{{ Auth::user()->prenom }} {{ Auth::user()->name }}</strong> !
                                </p>
                            </div>
                        </div>
                        <div class="d-none d-md-block">
                            @if(Auth::user()->hasRole('etudiant'))
                            <a href="{{ route('etudiant.export.emploi', ['groupe_id' => Auth::user()->etudiant->groupe_id]) }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                                <i class="fas fa-file-pdf me-2"></i>Télécharger PDF
                            </a> {{-- Changed icon to fas fa-file-pdf --}}
                            @endif
                            @if(Auth::user()->hasRole('professeur'))
                            <a href="{{ route('professeur.export.emploi', ['professeur_id' => Auth::user()->professeur->id]) }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                                <i class="fas fa-file-pdf me-2"></i>Télécharger PDF
                            </a> {{-- Changed icon to fas fa-file-pdf --}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 card-animated" data-delay="0.2"> {{-- Added card-animated and data-delay --}}
                <div class="card-body p-0">
                    <div class="calendar-wrapper">
                        <div id="calendar" class="bg-white rounded-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 card-animated" data-delay="0.3"> {{-- Added card-animated and data-delay --}}
                <div class="card-body py-3 px-4">
                    <div class="text-center d-flex justify-content-center flex-wrap gap-3 legend-calendar">
                        <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-calendar-times me-1"></i>Vacances / Fériés</span> {{-- Changed icon to fas fa-calendar-times --}}
                        <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-book me-1"></i>Cours (filières dynamiques)</span> {{-- Changed icon to fas fa-book --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Course Details Modal -->
<div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary-gradient text-white rounded-top-4"> {{-- Changed bg-gradient-primary to bg-primary-gradient --}}
                <h5 class="modal-title fw-bold"><i class="fas fa-info-circle me-2"></i>Détails de la séance</h5> {{-- Changed icon to fas fa-info-circle --}}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body p-4" id="modal-body">
                <!-- Content will be injected here -->
            </div>
        </div>
    </div>
</div>

<!-- Holiday Details Modal -->
<div class="modal fade" id="eventDetailsModal1" tabindex="-1" aria-labelledby="eventDetailsModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-gradient-danger text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-calendar-times me-2"></i>Détails de la vacance</h5> {{-- Changed icon to fas fa-calendar-times --}}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Content will be injected here -->
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canGerer = @json(Auth::user()->hasRole('professeur'));
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'fr',
            initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
            height: 'auto',
            aspectRatio: window.innerWidth < 768 ? 1.0 : 1.6,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: window.innerWidth < 768 ? 'listWeek,dayGridMonth' : 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Aujourd\'hui',
                month: 'Mois',
                week: 'Semaine',
                day: 'Jour',
                list: 'Liste'
            },
            slotMinTime: '08:00:00',
            slotMaxTime: '18:00:00',
            allDaySlot: false,
            eventDisplay: 'block',
            dayMaxEvents: window.innerWidth < 768 ? 3 : 5,
            moreLinkClick: 'popover',
            events: async function(fetchInfo, successCallback, failureCallback) {
                try {
                    const [coursRes, joursInactifsRes] = await Promise.all([
                        fetch("{{ $type === 'professeur' ? url('/api/professeur/sessions') : url('/api/etudiant/sessions') }}"),
                        fetch("{{ url('/api/jours-inactifs') }}")
                    ]);
                    const cours = await coursRes.json();
                    const joursInactifs = await joursInactifsRes.json();
                    successCallback([...cours, ...joursInactifs]);
                } catch (error) {
                    console.error("Erreur de chargement :", error);
                    failureCallback(error);
                }
            },
            eventClick: function(info) {
                const props = info.event.extendedProps;
                if (info.event.classNames.includes('vacance')) {
                    document.querySelector('#eventDetailsModal1 .modal-body').innerHTML = `
                        <div class="text-center">
                            <div class="mb-4">
                                <i class="fas fa-calendar-times display-4 text-danger"></i> {{-- Changed icon to fas fa-calendar-times --}}
                            </div>
                            <h5 class="mb-3">${info.event.title}</h5>
                            <p class="text-muted">Aucun cours programmé ce jour</p>
                        </div>`;
                    new bootstrap.Modal(document.getElementById('eventDetailsModal1')).show();
                    return;
                }
                let details = `
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card bg-light border-0 shadow-sm h-100">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-primary mb-3">
                                        <i class="fas fa-book me-2"></i>Informations du cours {{-- Changed icon to fas fa-book --}}
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Cours :</strong> ${info.event.title}</p>
                                            <p class="mb-2"><strong>Professeur :</strong> ${props.professeur ?? '-'}</p>
                                            <p class="mb-2"><strong>Salle :</strong> ${props.salle ?? '-'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Groupe :</strong> ${props.groupe ?? '-'}</p>
                                            <p class="mb-2"><strong>Filière :</strong> ${props.filiere ?? '-'}</p>
                                            <p class="mb-2"><strong>Heure :</strong> ${props.heure_debut ?? ''} - ${props.heure_fin ?? ''}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                if (canGerer) {
                    details += `
                        <div class="mt-3 text-center">
                            <a href="/presences/gerer/${info.event.id}" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-users me-2"></i>Gérer {{-- Changed icon to fas fa-users --}}
                            </a>
                        </div>`;
                }
                document.querySelector('#eventDetailsModal .modal-body').innerHTML = details;
                new bootstrap.Modal(document.getElementById('eventDetailsModal')).show();
            },
            eventMouseEnter: function(info) {
                info.el.style.transform = 'scale(1.02)';
                info.el.style.transition = 'transform 0.2s ease';
                info.el.style.zIndex = '999';
            },
            eventMouseLeave: function(info) {
                info.el.style.transform = 'scale(1)';
                info.el.style.zIndex = 'auto';
            }
        });
        calendar.render();

        // Responsive handling
        window.addEventListener('resize', function() {
            if (window.innerWidth < 768) {
                calendar.changeView('listWeek');
                calendar.setOption('aspectRatio', 1.0);
                calendar.setOption('dayMaxEvents', 3);
            } else {
                calendar.setOption('aspectRatio', 1.6);
                calendar.setOption('dayMaxEvents', 5);
            }
            calendar.updateSize();
        });

        // Smooth card entrance animation with delays
        const cards = document.querySelectorAll('.card-animated');
        cards.forEach((card) => {
            const delay = parseFloat(card.dataset.delay || '0');
            card.style.animationDelay = `${delay}s`;
        });
    });
</script>
@endsection

@push('styles')
<style>
    /* Custom gradient */
    .bg-primary-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        /* Standardized primary gradient */
    }

    .bg-gradient-primary {
        /* Keeping original for this dashboard if it's distinct */
        background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
    }

    .bg-gradient-danger {
        background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
        /* Consistent with layout */
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
        border-radius: 2rem;
        /* Standardized to rounded-pill */
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn:active {
        transform: translateY(0);
    }

    /* Calendar Maximum Space and Visibility */
    .calendar-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 1rem;
    }

    #calendar {
        min-width: 700px;
        width: 100%;
        min-height: 80vh;
        /* Grande hauteur pour visibilité maximale */
        font-size: 1rem;
        padding: 1rem;
    }

    /* Enhanced Calendar Header */
    .fc .fc-toolbar {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem;
        border-radius: 1rem 1rem 0 0;
        margin-bottom: 0 !important;
        border-bottom: 2px solid #dee2e6;
    }

    .fc .fc-toolbar-title {
        font-size: 2rem;
        font-weight: 700;
        color: #495057;
    }

    .fc .fc-button {
        background: #fff !important;
        border: 2px solid #dee2e6 !important;
        color: #495057 !important;
        border-radius: 0.75rem !important;
        padding: 0.75rem 1.25rem !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .fc .fc-button:hover {
        background: #e9ecef !important;
        border-color: #adb5bd !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .fc-button-primary:not(:disabled).fc-button-active {
        background: #0d6efd !important;
        border-color: #0d6efd !important;
        color: white !important;
    }

    /* Enhanced Event Styling */
    .fc-event {
        border: none !important;
        border-radius: 10px !important;
        padding: 8px 12px !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15) !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        margin: 2px !important;
        background-color: #0d6efd !important;
        /* Updated to new primary color */
        color: white !important;
    }

    .fc-event:hover {
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.25) !important;
        transform: translateY(-2px) scale(1.02) !important;
    }

    .fc-event.vacance {
        background-color: #dc3545 !important;
        color: #fff !important;
    }

    /* Day Grid Enhancements */
    .fc-daygrid-day {
        transition: background-color 0.3s ease;
        border: 1px solid #e9ecef !important;
        min-height: 120px !important;
        /* Plus de hauteur pour les cellules */
    }

    .fc-daygrid-day:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }

    .fc-day-today {
        background-color: rgba(13, 110, 253, 0.1) !important;
        border: 2px solid #0d6efd !important;
    }

    .fc-daygrid-day-number {
        font-weight: 600;
        font-size: 1.1rem;
        color: #495057;
        padding: 8px;
    }

    .fc-day-today .fc-daygrid-day-number {
        color: #0d6efd;
        font-weight: 700;
    }

    /* Week and Day View Enhancements */
    .fc-timegrid-slot {
        height: 4rem !important;
        /* Plus d'espace pour les créneaux horaires */
        border-color: #e9ecef !important;
    }

    .fc-timegrid-axis {
        background: #f8f9fa;
        border-color: #dee2e6 !important;
        font-weight: 600;
    }

    /* Modal Enhancements */
    .modal-content {
        border-radius: 1.5rem;
        overflow: hidden;
        border: none;
    }

    .modal-header {
        border: none;
        padding: 2rem 2rem 1rem;
    }

    .modal-body {
        padding: 1rem 2rem 2rem;
    }

    /* Legend Enhancements */
    .legend-calendar .badge {
        padding: 8px 16px;
        font-size: 0.9rem;
        border-radius: 2rem;
        /* Standardized to rounded-pill */
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }

    .legend-calendar .badge:hover {
        transform: translateY(-1px);
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

    /* Responsive Improvements */
    @media (max-width: 768px) {
        #calendar {
            min-width: 100%;
            min-height: 60vh;
            font-size: 0.85rem;
            padding: 0.5rem;
        }

        .fc .fc-toolbar {
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
            padding: 1rem;
        }

        .fc .fc-toolbar-title {
            font-size: 1.5rem;
            margin: 0.5rem 0;
        }

        .fc .fc-button {
            font-size: 0.8rem !important;
            padding: 0.5rem 1rem !important;
        }

        .fc-event {
            font-size: 0.8rem !important;
            padding: 4px 8px !important;
        }

        .fc-daygrid-day {
            min-height: 80px !important;
        }

        .fc-daygrid-day-number {
            font-size: 1rem;
            padding: 4px;
        }
    }

    @media (max-width: 576px) {
        #calendar {
            min-height: 50vh;
        }

        .fc .fc-toolbar-title {
            font-size: 1.2rem;
        }

        .fc .fc-button {
            font-size: 0.75rem !important;
            padding: 0.4rem 0.8rem !important;
        }

        .fc-event {
            font-size: 0.75rem !important;
            padding: 3px 6px !important;
        }

        .fc-daygrid-day {
            min-height: 60px !important;
        }
    }

    /* Custom scrollbar */
    .calendar-wrapper::-webkit-scrollbar {
        height: 10px;
    }

    .calendar-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 5px;
    }

    .calendar-wrapper::-webkit-scrollbar-thumb {
        background: #0d6efd;
        border-radius: 5px;
    }

    .calendar-wrapper::-webkit-scrollbar-thumb:hover {
        background: #0056b3;
    }
</style>
@endpush