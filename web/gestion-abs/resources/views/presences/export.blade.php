<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Feuille de présence</title>
    <link rel="icon" type="image/png" href="{{ asset('/assets/images/icon.png') }}" />
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 15px;
            color: #2c3e50;
            line-height: 1.3;
            background-color: white;
        }

        /* En-tête du document - VERSION COMPACTE */
        .header-container {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 3px solid #003366;
            padding-bottom: 12px;
        }

        .header-table {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            padding: 5px;
            vertical-align: top;
        }

        .logo-cell {
            width: 12%;
        }

        .logo {
            width: 65px;
            height: auto;
            border: 2px solid #003366;
            border-radius: 8px;
            padding: 5px;
            background-color: white;
        }

        .info-cell {
            width: 88%;
        }

        .document-title {
            font-size: 20pt;
            font-weight: bold;
            color: #003366;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .info-table td {
            padding: 3px 8px;
            border: none;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
            color: #003366;
            width: 20%;
        }

        .info-value {
            color: #2c3e50;
            width: 80%;
        }

        /* Section statistiques - VERSION COMPACTE */
        .stats-container {
            background-color: #f9f9f9;
            border-left: 6px solid #003366;
            padding: 12px;
            margin: 15px 0;
            border-radius: 0 8px 8px 0;
        }

        .stats-title {
            font-size: 12pt;
            font-weight: bold;
            color: #003366;
            margin-bottom: 8px;
        }

        .stats-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .stats-grid td {
            border: none;
            padding: 3px 8px;
            vertical-align: middle;
        }

        .stat-box {
            background-color: white;
            border: 2px solid #003366;
            border-radius: 8px;
            padding: 8px;
            text-align: center;
            width: 33%;
        }

        .stat-number {
            font-size: 16pt;
            font-weight: bold;
            color: #003366;
            display: block;
        }

        .stat-label {
            font-size: 8pt;
            color: #2c3e50;
            text-transform: uppercase;
            font-weight: bold;
        }

        .stat-present .stat-number {
            color: #28a745;
        }

        .stat-absent .stat-number {
            color: #dc3545;
        }

        /* Tableau des présences - VERSION COMPACTE */
        .presence-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 9pt;
            border: 2px solid #003366;
        }

        .presence-table th {
            background-color: #003366;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #002244;
            padding: 8px 6px;
            text-align: center;
            font-size: 8pt;
            letter-spacing: 0.5px;
        }

        .presence-table td {
            border: 1px solid #bbb;
            padding: 6px 5px;
            vertical-align: middle;
        }

        /* Largeurs des colonnes - OPTIMISÉES */
        .col-numero {
            width: 6%;
            text-align: center;
        }

        .col-nom {
            width: 32%;
        }

        .col-etat {
            width: 12%;
            text-align: center;
        }

        .col-remarque {
            width: 35%;
        }

        .col-bonus {
            width: 15%;
            text-align: center;
        }

        /* Styles des lignes */
        .presence-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .presence-table tbody tr:nth-child(odd) {
            background-color: white;
        }

        /* Styles pour les cellules - VERSION COMPACTE */
        .numero-cell {
            font-weight: bold;
            color: #003366;
            background-color: #e6f0ff;
            text-align: center;
            font-size: 9pt;
        }

        .nom-cell {
            font-weight: bold;
            color: #2c3e50;
            font-size: 9pt;
        }

        .present-cell {
            color: #28a745;
            font-weight: bold;
            background-color: #d4edda;
            text-align: center;
            border-radius: 4px;
            padding: 3px;
            font-size: 8pt;
        }

        .absent-cell {
            color: #dc3545;
            font-weight: bold;
            background-color: #f8d7da;
            text-align: center;
            border-radius: 4px;
            padding: 3px;
            font-size: 8pt;
        }

        .remarque-cell {
            color: #6c757d;
            font-style: italic;
            font-size: 8pt;
        }

        .bonus-cell {
            font-weight: bold;
            color: #003366;
            text-align: center;
            background-color: #e6f0ff;
            font-size: 8pt;
        }

        /* Pied de page - VERSION COMPACTE */
        .footer-container {
            margin-top: 25px;
            width: 100%;
        }

        .footer-table {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }

        .footer-table td {
            border: none;
            padding: 5px;
            vertical-align: top;
        }

        .signature-cell {
            width: 65%;
        }

        .signature-box {
            border: 2px dashed #003366;
            padding: 12px;
            width: 75%;
            height: 60px;
            position: relative;
        }

        .signature-label {
            position: absolute;
            top: -10px;
            left: 10px;
            background-color: white;
            padding: 0 8px;
            font-weight: bold;
            color: #003366;
            font-size: 9pt;
        }

        .signature-date {
            position: absolute;
            bottom: 5px;
            right: 10px;
            font-size: 7pt;
            color: #6c757d;
        }

        .qr-cell {
            width: 35%;
            text-align: right;
        }

        .qr-container {
            background-color: white;
            border: 2px solid #003366;
            border-radius: 8px;
            padding: 8px;
            display: inline-block;
            text-align: center;
        }

        .qr-code {
            width: 80px;
            height: auto;
        }

        .qr-label {
            margin-top: 3px;
            font-size: 7pt;
            color: #003366;
            font-weight: bold;
        }

        /* Légende - VERSION COMPACTE */
        .legend-container {
            margin-top: 15px;
            border-top: 1px dashed #bbb;
            padding-top: 8px;
            font-size: 7pt;
            color: #6c757d;
        }

        .legend-title {
            font-weight: bold;
            margin-bottom: 3px;
        }

        /* Configuration page */
        @page {
            size: A4 portrait;
            margin: 1.2cm 1cm;
        }

        /* Optimisations impression */
        @media print {
            body {
                font-size: 9pt;
                padding: 12px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .presence-table {
                page-break-inside: auto;
            }

            .presence-table tr {
                page-break-inside: avoid;
            }

            .presence-table thead {
                display: table-header-group;
            }

            .footer-container {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <!-- En-tête du document -->
    <div class="header-container">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
                </td>
                <td class="info-cell">
                    <div class="document-title">Feuille de présence</div>

                    <table class="info-table">
                        <tr>
                            <td class="info-label">Date :</td>
                            <td class="info-value">{{ \Carbon\Carbon::parse($seance->date)->translatedFormat('l d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Horaire :</td>
                            <td class="info-value">{{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Professeur :</td>
                            <td class="info-value">{{ $seance->professeur->user->prenom }} {{ $seance->professeur->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Cours :</td>
                            <td class="info-value">{{ $seance->cours->nom }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Groupe :</td>
                            <td class="info-value">{{ $seance->groupe->nom_groupe }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- Section statistiques -->
    <div class="stats-container">
        <div class="stats-title">Statistiques de présence</div>

        <table class="stats-grid">
            <tr>
                <td>
                    <div class="stat-box">
                        <span class="stat-number">{{ $totalEtudiants }}</span>
                        <span class="stat-label">Total étudiants</span>
                    </div>
                </td>
                <td>
                    <div class="stat-box stat-present">
                        <span class="stat-number">{{ $totalPresents }}</span>
                        <span class="stat-label">Présents</span>
                    </div>
                </td>
                <td>
                    <div class="stat-box stat-absent">
                        <span class="stat-number">{{ $totalAbsents }}</span>
                        <span class="stat-label">Absents</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Tableau des présences -->
    <table class="presence-table">
        <thead>
            <tr>
                <th class="col-numero">N°</th>
                <th class="col-nom">Nom complet</th>
                <th class="col-etat">État</th>
                <th class="col-remarque">Remarque</th>
                <th class="col-bonus">Bonus</th>
                <th class="col-bonus">Retard (min)</th>
                <th><i class="bi bi-journal-text me-1"></i>Justification</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach($seance->groupe->etudiants as $etudiant)
            @php
            $presence = $seance->presences->firstWhere('id_etudiant', $etudiant->id);
            $etat = $presence?->etat ?? 1;
            $remarque = $presence?->remarque ?? '';
            $bonus = $presence?->bonus ?? '';
            $retard = $presence?->retard ?? 0;
            @endphp
            <tr>
                <td class="numero-cell">{{ str_pad($i++, 2, '0', STR_PAD_LEFT) }}</td>
                <td class="nom-cell">{{ $etudiant->user->prenom }} {{ $etudiant->user->name }}</td>
                <td>
                    <div class="{{ $etat == 1 ? 'present-cell' : 'absent-cell' }}">
                        {{ $etat == 1 ? 'Présent' : 'Absent' }}
                    </div>
                </td>
                <td class="remarque-cell">{{ $remarque ?: '—' }}</td>
                <td class="bonus-cell">{{ $bonus ?: '0' }}</td>
                <td class="bonus-cell">{{ $retard ?: '0' }}</td>
                <td>
                    @if($etat == 0)
                    <div class="d-flex align-items-center gap-1">
                        @if(trim($presence?->justification ?? '') !== '')
                        <span class="present-cell">Justifiée</span>
                        @else
                        <span class="absent-cell">Non justifiée</span>
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

    <!-- Pied de page avec signature et QR -->
    <div class="footer-container">
        <table class="footer-table">
            <tr>
                <td class="signature-cell">
                    <div class="signature-box">
                        <div class="signature-label">Signature du professeur</div>
                        <p>{{ $seance->professeur->user->prenom }} {{ $seance->professeur->user->name }}</p>
                        <div class="signature-date">{{ \Carbon\Carbon::parse($seance->date)->format('d/m/Y') }}</div>
                    </div>
                </td>
                <td class="qr-cell">
                    <div class="qr-container">
                        <img src="data:image/png;base64,{{ $qrCode }}" class="qr-code" alt="QR Code">
                        <div class="qr-label">Code de vérification</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Légende et informations supplémentaires -->
    <div class="legend-container">
        <div class="legend-title">Informations :</div>
        <div>• Ce document est généré automatiquement le {{ date('d/m/Y à H:i') }}</div>
        <div>• Le QR code permet de vérifier l'authenticité de ce document</div>
        <div>• Les bonus sont attribués pour participation active en classe</div>
    </div>
</body>

</html>