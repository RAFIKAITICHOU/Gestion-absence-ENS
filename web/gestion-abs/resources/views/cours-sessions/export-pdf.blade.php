<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Planning des Séances</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9pt;
            margin: 0;
            padding: 15px;
            color: #333;
            line-height: 1.3;
        }

        /* En-tête simplifié compatible DomPDF */
        .header-container {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 15px;
        }

        .header-row {
            width: 100%;
        }

        .header-row td {
            vertical-align: top;
            border: none;
            padding: 5px;
        }

        .logo {
            width: 70px;
            height: auto;
        }

        h1 {
            color: #0d6efd;
            font-size: 16pt;
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
        }

        .info-line {
            font-weight: bold;
            margin: 3px 0;
            font-size: 9pt;
            color: #495057;
            text-align: center;
        }

        .date-info {
            text-align: right;
            font-size: 8pt;
            color: #6c757d;
        }

        /* Tableau simplifié pour DomPDF */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 8pt;
        }

        .main-table th {
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 8px 4px;
            border: 1px solid #333;
            font-size: 7pt;
            text-transform: uppercase;
        }

        .main-table td {
            border: 1px solid #ccc;
            padding: 6px 4px;
            text-align: center;
            vertical-align: middle;
            font-size: 8pt;
        }

        /* Largeurs de colonnes fixes */
        .col-semaine {
            width: 8%;
        }

        .col-date {
            width: 10%;
        }

        .col-heure {
            width: 12%;
        }

        .col-cours {
            width: 20%;
        }

        .col-prof {
            width: 18%;
        }

        .col-salle {
            width: 8%;
        }

        .col-groupe {
            width: 12%;
        }

        .col-filiere {
            width: 12%;
        }

        /* Alternance de couleurs simple */
        .main-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .main-table tr:nth-child(odd) {
            background-color: white;
        }

        /* Cellule semaine */
        .week-cell {
            background-color: #e3f2fd !important;
            font-weight: bold;
            color: #0d6efd;
            font-size: 8pt;
        }

        /* Styles pour les données */
        .course-name {
            font-weight: bold;
            color: #212529;
        }

        .time-range {
            font-weight: bold;
            color: #0d6efd;
        }

        .room-info {
            background-color: #6c757d;
            color: white;
            padding: 1px 4px;
            font-size: 7pt;
            font-weight: bold;
        }

        /* Section vacances simplifiée */
        .vacances-section {
            margin-top: 20px;
            padding: 10px;
            background-color: #fff3cd;
            border: 2px solid #ffc107;
        }

        .vacances-title {
            font-weight: bold;
            font-size: 11pt;
            color: #856404;
            margin: 0 0 10px 0;
            text-transform: uppercase;
        }

        .vacances-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }

        .vacances-table td {
            border: 1px solid #ffc107;
            padding: 5px;
            vertical-align: middle;
        }

        .vacances-table .title-col {
            background-color: #ffc107;
            color: #856404;
            font-weight: bold;
            width: 30%;
        }

        .vacances-table .date-col {
            background-color: white;
            color: #856404;
            font-weight: bold;
        }

        /* Pied de page simple */
        .footer {
            margin-top: 20px;
            padding: 8px 0;
            border-top: 2px solid #0d6efd;
            text-align: center;
            font-size: 7pt;
            color: #6c757d;
        }

        /* Configuration page paysage */
        @page {
            size: A4 landscape;
            margin: 1.5cm 1cm;
        }

        /* Optimisations impression */
        @media print {
            body {
                font-size: 8pt;
            }

            .main-table {
                page-break-inside: auto;
            }

            .main-table tr {
                page-break-inside: avoid;
            }

            .main-table thead {
                display: table-header-group;
            }
        }
    </style>
</head>

<body>
    <!-- En-tête avec tableau simple -->
    <div class="header-container">
        <table class="header-row" style="border: none;">
            <tr>
                <td style="width: 15%; text-align: left;">
                    <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo">
                </td>
                <td style="width: 70%; text-align: center;">
                    <h1>Planning des Séances</h1>
                    @if($professeur)
                    <div class="info-line">
                        Professeur : {{ $professeur->user->name }} {{ $professeur->user->prenom }}
                    </div>
                    @endif

                    @if($groupe)
                    <div class="info-line">
                        Groupe : {{ $groupe->nom_groupe }} | Filière : {{ $groupe->filiere->nom_filiere ?? '-' }}
                    </div>
                    @endif
                </td>
                <td style="width: 15%; text-align: right;">
                    <div class="date-info">
                        <strong>Document officiel</strong><br>
                        {{ date('d/m/Y') }}<br>
                        {{ date('H:i') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Tableau principal -->
    <table class="main-table">
        <thead>
            <tr>
                <th class="col-semaine">Semaine</th>
                <th class="col-date">Date</th>
                <th class="col-heure">Horaires</th>
                <th class="col-cours">Matière / Cours</th>
                <th class="col-prof">Enseignant</th>
                <th class="col-salle">Salle</th>
                <th class="col-groupe">Groupe</th>
                <th class="col-filiere">Filière</th>
            </tr>
        </thead>

        <tbody>
            @foreach($seancesGrouped as $semaine => $groupeSeances)
            @foreach($groupeSeances as $index => $s)
            <tr>
                @if($index === 0)
                <td rowspan="{{ $groupeSeances->count() }}" class="week-cell">
                    Semaine<br><strong>{{ $semaine }}</strong>
                </td>
                @endif

                <td>{{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}</td>
                <td class="time-range">{{ $s->heure_debut }} → {{ $s->heure_fin }}</td>
                <td class="course-name">{{ $s->cours->nom }}</td>
                <td>{{ $s->professeur->user->name ?? '' }} {{ $s->professeur->user->prenom ?? '' }}</td>
                <td><span class="room-info">{{ $s->salle->nom }}</span></td>
                <td><strong>{{ $s->groupe->nom_groupe }}</strong></td>
                <td>{{ $s->groupe->filiere->nom_filiere ?? '-' }}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>

    <!-- Section vacances -->
    @if($joursInactifs->count())
    <div class="vacances-section">
        <div class="vacances-title">Jours inactifs / vacances</div>
        <table class="vacances-table">
            @foreach($joursInactifs as $j)
            <tr>
                <td class="title-col">{{ $j->titre }}</td>
                <td class="date-col">
                    {{ \Carbon\Carbon::parse($j->date_debut)->format('d/m/Y') }}
                    @if($j->date_fin)
                    → {{ \Carbon\Carbon::parse($j->date_fin)->format('d/m/Y') }}
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <!-- Pied de page -->
    <div class="footer">
        <strong>Planning Académique Officiel</strong> - Généré automatiquement le {{ date('d/m/Y à H:i') }}
    </div>
</body>

</html>