@extends('layouts.' . $menu)

@section('breadcrumb', 'Annonces officielles')
@section('title', 'Mes annonces')

@section('content')
<div class="container-fluid py-3">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-primary-gradient text-white rounded-4 card-animated" data-delay="0.1"> 
                <div class="card-body py-4 px-4 d-flex align-items-center">
                    <i class="fas fa-bullhorn display-6 me-3"></i>  
                    <div>
                        
                        <h2 class="mb-0 fw-bold"> Annonces officielles</h2>
                        <p class="mb-0 opacity-75">Restez informé des dernières communications importantes.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements -->
    @forelse($annonces as $index => $annonce)  
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 announcement-card border-start border-{{ $annonce->type }} border-5 card-animated" data-delay="{{ 0.2 + ($index * 0.1) }}">  
                <div class="card-body p-4">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h4 class="fw-bold text-{{ $annonce->type }} mb-2">
                                {{ $annonce->titre }}
                            </h4>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <span class="badge bg-{{ $annonce->type }} rounded-pill px-3 py-2">
                                    @switch($annonce->type)
                                    @case('primary')<i class="fas fa-info-circle me-1"></i>Information   
                                    @break
                                    @case('success')<i class="fas fa-check-circle me-1"></i>Succès 
                                    @break
                                    @case('warning')<i class="fas fa-exclamation-triangle me-1"></i>Attention 
                                    @break
                                    @case('danger')<i class="fas fa-exclamation-circle me-1"></i>Urgent 
                                    @break
                                    @default<i class="fas fa-bell me-1"></i>Annonce 
                                    @endswitch
                                </span>
                                <small class="text-muted"><i class="fas fa-calendar-alt me-1"></i> 
                                    Publiée le {{ $annonce->created_at->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                    </div>
                    <!-- Content -->
                    <div class="mb-3">
                        <p class="mb-0 lh-base text-dark">{{ $annonce->contenu }}</p>
                    </div>
                    <!-- Media -->
                    @if($annonce->media)
                    <div class="mb-4 media-preview">
                        @php
                        $mediaPath = asset('storage/' . $annonce->media);
                        $extension = Str::lower(pathinfo($annonce->media, PATHINFO_EXTENSION));
                        @endphp
                        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                        <div class="text-center"><img src="{{ $mediaPath }}" alt="Image annonce" class="img-fluid rounded-3 border shadow-sm" style="max-height: 400px; object-fit: contain;"></div>
                        @elseif($extension === 'mp4')
                        <div class="text-center"><video controls class="w-100 rounded-3 border shadow-sm" style="max-height: 400px;">
                                <source src="{{ $mediaPath }}" type="video/mp4">
                                Votre navigateur ne supporte pas la lecture vidéo.
                            </video></div>
                        @elseif($extension === 'pdf')
                        <div class="text-center">
                            <!-- PDF Viewer for desktop -->
                            <iframe src="{{ $mediaPath }}" class="w-100 rounded-3 border shadow-sm d-none d-md-block" style="height: 500px;" frameborder="0"></iframe>
                            <!-- PDF Link for mobile -->
                            <div class="d-block d-md-none"><a href="{{ $mediaPath }}" target="_blank" class="btn btn-outline-primary rounded-pill px-4"><i class="fas fa-file-pdf me-2"></i>Ouvrir le PDF</a></div>  
                        </div>
                        @elseif(in_array($extension, ['doc', 'docx', 'xls', 'xlsx', 'csv', 'zip']))
                        <div class="text-center"><a href="{{ $mediaPath }}" class="btn btn-outline-primary rounded-pill px-4" download><i class="fas fa-download me-2"></i>Télécharger le fichier<small class="d-block mt-1 text-muted">{{ strtoupper($extension) }}</small></a></div>  
                        @else
                        <div class="text-center text-muted py-4 border rounded-3 bg-light"><i class="fas fa-file display-6 mb-2"></i> 
                            <p class="mb-0">Type de fichier non supporté pour l'aperçu</p><a href="{{ $mediaPath }}" class="btn btn-sm btn-outline-secondary mt-2" download><i class="fas fa-download me-1"></i> Télécharger quand même</a>
                        </div> 
                        @endif
                    </div>
                    @endif
                    <!-- Footer -->
                    <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                        <div class="text-muted small"><i class="fas fa-eye me-1"></i>  
                            Annonce officielle</div>
                        @if($annonce->media)
                        <div class="text-muted small"><i class="fas fa-paperclip me-1"></i> 
                            Pièce jointe</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <!-- Empty State -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body text-center py-5">
                    <div class="text-muted">
                        <i class="bi bi-megaphone display-4 mb-3"></i>
                        <h5>Aucune annonce disponible</h5>
                        <p class="mb-0">Il n'y a pas d'annonces officielles pour le moment.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforelse
    <!-- Summary -->
    @if($annonces->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-light rounded-4 card-animated" data-delay="{{ 0.2 + ($annonces->count() * 0.1) }}">  
                <div class="card-body p-3 text-center"><small class="text-muted"><i class="fas fa-info-circle me-1"></i>  
                        {{ $annonces->count() }} annonce(s) affichée(s)</small></div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    /* Custom Gradient for Header */
    .bg-primary-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    /* Card and General Element Styling */
    .card {
        border-radius: 1rem;
        /* More rounded corners */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
    }

    /* Announcement Card Specifics */
    .announcement-card {
        border-left: 5px solid;
        /* Thicker border for type indication */
        transition: border-left-width 0.3s ease;
    }

    .announcement-card:hover {
        border-left-width: 8px;
        /* Thicker on hover */
    }

    /* Badges for Type and Date */
    .badge {
        font-size: 0.85rem;
        padding: 0.6rem 1.2rem;
        border-radius: 50px;
        /* Pill shape */
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .badge i {
        font-size: 0.9em;
    }

    /* Type Colors */
    .text-primary {
        color: #0d6efd !important;
    }

    .text-success {
        color: #198754 !important;
    }

    .text-warning {
        color: #ffc107 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .border-primary {
        border-color: #0d6efd !important;
    }

    .border-success {
        border-color: #198754 !important;
    }

    .border-warning {
        border-color: #ffc107 !important;
    }

    .border-danger {
        border-color: #dc3545 !important;
    }

    .bg-primary {
        background-color: #0d6efd !important;
    }

    .bg-success {
        background-color: #198754 !important;
    }

    .bg-warning {
        background-color: #ffc107 !important;
        color: #000 !important;
    }

    /* Ensure text is black on yellow */
    .bg-danger {
        background-color: #dc3545 !important;
    }

    /* Content Styling */
    .lh-base {
        line-height: 1.7;
        /* Increased line height for readability */
        color: #343a40;
        /* Darker text for better contrast */
    }

    /* Media Styling */
    .media-preview img,
    .media-preview video,
    .media-preview iframe {
        max-width: 100%;
        height: auto;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        /* Softer shadow for media */
        border-radius: 0.75rem;
        /* Rounded corners for media */
    }

    .media-preview iframe {
        min-height: 400px;
        /* Ensure PDF viewer has enough height */
    }

    /* Buttons */
    .btn {
        border-radius: 0.5rem;
        /* Consistent border-radius for buttons */
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Empty State Styling */
    .text-muted i.display-4 {
        font-size: 3.5rem !important;
        /* Larger icon */
        opacity: 0.6;
    }

    .text-muted h5 {
        font-weight: 600;
        color: #6c757d;
    }

    /* Footer Styling */
    .border-top {
        border-color: #e9ecef !important;
    }

    .text-muted.small {
        font-size: 0.875rem;
        color: #6c757d !important;
    }

    .text-muted.small i {
        opacity: 0.8;
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

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 0.75rem;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 1rem;
        }

        .media-preview iframe {
            height: 350px !important;
        }

        .media-preview video {
            height: 300px !important;
        }

        .media-preview img {
            max-height: 300px !important;
        }

        .announcement-card {
            border-left-width: 4px;
        }

        .announcement-card:hover {
            border-left-width: 6px;
        }

        h4 {
            font-size: 1.25rem;
        }
    }

    @media (max-width: 576px) {
        .card-body {
            padding: 1rem !important;
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.4rem 0.8rem;
        }

        .btn {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
        }

        .display-4 {
            font-size: 2rem !important;
        }

        .media-preview iframe {
            height: 250px !important;
        }

        .media-preview video {
            height: 200px !important;
        }

        .media-preview img {
            max-height: 200px !important;
        }

        h4 {
            font-size: 1.1rem;
        }
    }

    /* Print Styles */
    @media print {

        .btn,
        .modal,
        .media-preview .d-block.d-md-none {
            display: none !important;
        }

        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
            break-inside: avoid;
            margin-bottom: 1rem;
        }

        .bg-primary-gradient,
        .bg-primary {
            background-color: #667eea !important;
            /* Use the specific color for print */
            -webkit-print-color-adjust: exact;
            color: white !important;
        }

        .announcement-card {
            border-left: 4px solid !important;
            -webkit-print-color-adjust: exact;
        }

        .text-primary,
        .text-success,
        .text-warning,
        .text-danger {
            color: inherit !important;
            /* Use default print color */
        }

        .badge {
            background-color: #f8f9fa !important;
            color: #343a40 !important;
            border: 1px solid #dee2e6 !important;
            -webkit-print-color-adjust: exact;
        }

        .media-preview video,
        .media-preview iframe {
            display: none !important;
            /* Hide video/iframe in print */
        }

        .media-preview img {
            max-height: 200px !important;
            width: auto !important;
        }

        .media-preview .btn-outline-primary {
            display: block !important;
            /* Show download button for files */
        }
    }

    /* Focus States for Accessibility */
    .btn:focus {
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        /* Consistent purple/blue focus */
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth card entrance animation with delays
        const cards = document.querySelectorAll('.card-animated');
        cards.forEach((card) => {
            const delay = parseFloat(card.dataset.delay || '0');
            card.style.animationDelay = `${delay}s`;
        });

        // Auto-dismiss alerts after 5 seconds (if any alerts are added in the future)
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                if (alert && alert.parentNode) {
                    alert.style.transition = 'all 0.5s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 500);
                }
            }, 5000);
        });
    });
</script>
@endpush