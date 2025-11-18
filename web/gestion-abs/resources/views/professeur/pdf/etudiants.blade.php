<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Liste des étudiants prof</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            margin: 0;
            padding: 20px;
            color: #2c3e50;
            line-height: 1.4;
            background-color: white;
        }

        /* En-tête élégant */
        .header-container {
            width: 100%;
            margin-bottom: 25px;
            background-color: #f8f9fa;
            border: 2px solid #0d6efd;
            border-radius: 10px;
            padding: 20px;
        }

        .header-content {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }

        .header-content td {
            border: none;
            padding: 10px;
            vertical-align: top;
        }

        .logo-section {
            width: 25%;
            text-align: center;
        }

        .logo {
            width: 90px;
            height: auto;
            border: 2px solid #0d6efd;
            border-radius: 8px;
            padding: 5px;
            background-color: white;
        }

        .title-section {
            width: 50%;
            text-align: center;
        }

        .main-title {
            font-size: 22pt;
            font-weight: bold;
            color: #0d6efd;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .subtitle {
            font-size: 12pt;
            color: #6c757d;
            font-style: italic;
            margin: 0;
        }

        .info-section {
            width: 25%;
            text-align: right;
        }

        .info-box {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 10px;
            font-size: 9pt;
        }

        .info-line {
            margin: 3px 0;
            color: #495057;
        }

        .info-label {
            font-weight: bold;
            color: #0d6efd;
        }

        .info-value {
            color: #212529;
        }

        /* Section statistiques */
        .stats-bar {
            background-color: #e3f2fd;
            border: 2px solid #2196f3;
            border-radius: 8px;
            padding: 12px;
            margin: 20px 0;
            text-align: center;
        }

        .stats-content {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }

        .stats-content td {
            border: none;
            padding: 5px 15px;
            text-align: center;
        }

        .stat-number {
            font-size: 18pt;
            font-weight: bold;
            color: #2196f3;
            display: block;
        }

        .stat-label {
            font-size: 9pt;
            color: #1976d2;
            text-transform: uppercase;
            font-weight: bold;
        }

        /* Tableau des étudiants - VERSION SIMPLIFIÉE */
        .students-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 9pt;
            border: 2px solid #0d6efd;
        }

        /* EN-TÊTE SIMPLIFIÉ POUR COMPATIBILITÉ */
        .students-table th {
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #333;
            padding: 12px 8px;
            text-align: center;
            font-size: 8pt;
            letter-spacing: 0.5px;
            vertical-align: middle;
        }

        .students-table td {
            border: 1px solid #e9ecef;
            padding: 10px 8px;
            vertical-align: middle;
        }

        /* Largeurs des colonnes */
        .col-numero {
            width: 8%;
            text-align: center;
        }

        .col-nom {
            width: 25%;
        }

        .col-prenom {
            width: 25%;
        }

        .col-cne {
            width: 20%;
            text-align: center;
        }

        .col-email {
            width: 22%;
        }

        /* Styles des lignes */
        .students-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .students-table tbody tr:nth-child(odd) {
            background-color: white;
        }

        /* Styles pour les cellules */
        .numero-cell {
            font-weight: bold;
            color: #0d6efd;
            background-color: #e3f2fd;
            text-align: center;
            font-size: 10pt;
        }

        .nom-cell {
            font-weight: bold;
            color: #212529;
            text-transform: uppercase;
        }

        .prenom-cell {
            color: #495057;
            text-transform: capitalize;
            font-weight: 600;
        }

        .cne-cell {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #6c757d;
            text-align: center;
            background-color: #f1f3f4;
        }

        .email-cell {
            font-family: 'Courier New', monospace;
            color: #0d6efd;
            font-size: 8pt;
        }

        /* Section QR Code */
        .qr-section {
            margin-top: 30px;
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            page-break-inside: avoid;
        }

        .qr-title {
            font-size: 14pt;
            font-weight: bold;
            color: #856404;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .qr-container {
            background-color: white;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
            display: inline-block;
        }

        .qr-code {
            max-width: 120px;
            height: auto;
            border: 1px solid #dee2e6;
        }

        .qr-description {
            margin-top: 10px;
            font-size: 9pt;
            color: #856404;
            font-style: italic;
        }

        /* Pied de page */
        .footer {
            margin-top: 30px;
            padding: 15px 0;
            border-top: 3px solid #0d6efd;
            background-color: #f8f9fa;
            text-align: center;
            font-size: 8pt;
            color: #6c757d;
            margin-left: -20px;
            margin-right: -20px;
            padding-left: 20px;
            padding-right: 20px;
        }

        .footer-grid {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }

        .footer-grid td {
            border: none;
            padding: 5px;
        }

        .footer-left {
            text-align: left;
            width: 33%;
        }

        .footer-center {
            text-align: center;
            width: 34%;
            font-weight: bold;
            color: #0d6efd;
        }

        .footer-right {
            text-align: right;
            width: 33%;
        }

        /* Signature */
        .signature-area {
            margin-top: 40px;
            text-align: right;
        }

        .signature-box {
            border: 2px dashed #dee2e6;
            width: 250px;
            height: 80px;
            margin-left: auto;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            position: relative;
        }

        .signature-label {
            font-size: 9pt;
            color: #6c757d;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .signature-date {
            position: absolute;
            bottom: 5px;
            right: 10px;
            font-size: 8pt;
            color: #adb5bd;
        }

        /* Configuration page */
        @page {
            size: A4 portrait;
            margin: 1.5cm 1cm;
        }

        /* Optimisations impression */
        @media print {
            body {
                font-size: 10pt;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .students-table {
                page-break-inside: auto;
            }

            .students-table tr {
                page-break-inside: avoid;
            }

            .students-table thead {
                display: table-header-group;
            }

            .qr-section,
            .signature-area {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <!-- En-tête professionnel -->
    <div class="header-container">
        <table class="header-content">
            <tr>
                <td class="logo-section">
                    <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
                </td>
                <td class="title-section">
                    <div class="main-title">Liste des étudiants</div>
                </td>
                <td class="info-section">
                    <div class="info-box">
                        <div class="info-line">
                            <span class="info-label">Professeur :</span><br>
                            <span class="info-value">{{ $professeur->user->name ?? '' }} {{ $professeur->user->prenom ?? '' }}</span>
                        </div>
                        <div class="info-line">
                            <span class="info-label">Cours :</span><br>
                            <span class="info-value">{{ $coursData->nom }}</span>
                        </div>
                        <div class="info-line">
                            <span class="info-label">Filière :</span><br>
                            <span class="info-value">{{ $filiereData->nom_filiere ?? '' }}</span>
                        </div>
                        <div class="info-line">
                            <span class="info-label">Groupe :</span><br>
                            <span class="info-value">{{ $groupeData->nom_groupe ?? '' }}</span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Barre de statistiques -->
    <div class="stats-bar">
        <table class="stats-content">
            <tr>
                <td>
                    <span class="stat-number">{{ count($etudiants) }}</span>
                    <span class="stat-label">Étudiants inscrits</span>
                </td>
                <td>
                    <span class="stat-number">{{ date('Y') }}</span>
                    <span class="stat-label">Année académique</span>
                </td>
                <td>
                    <span class="stat-number">{{ date('d/m') }}</span>
                    <span class="stat-label">Date d'édition</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Tableau des étudiants avec en-tête simplifié -->
    <table class="students-table">
        <thead>
            <tr>
                <th class="col-numero">N°</th>
                <th class="col-nom">Nom de famille</th>
                <th class="col-prenom">Prénom</th>
                <th class="col-cne">Code National</th>
                <th class="col-email">Adresse e-mail</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($etudiants as $etudiant)
            <tr>
                <td class="numero-cell">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                <td class="nom-cell">{{ $etudiant->user->name ?? '' }}</td>
                <td class="prenom-cell">{{ $etudiant->user->prenom ?? '' }}</td>
                <td class="cne-cell">{{ $etudiant->cne }}</td>
                <td class="email-cell">{{ $etudiant->user->email ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Section QR Code -->
    <div class="qr-section">
        <div class="qr-title">Code de vérification numérique</div>
        <div class="qr-container">
            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" class="qr-code">
        </div>
    </div>

    <!-- Zone de signature -->
    <div class="signature-area">
        <div class="signature-label">Signature  :</div>
        <div class="signature-box">
            <p class="text-center">ENS MARRAKECH</p>
            <div class="signature-date">{{ date('d/m/Y') }}</div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <table class="footer-grid">
            <tr>
                <td class="footer-left">
                    <strong>Document certifié</strong><br>
                    Généré automatiquement
                </td>
                <td class="footer-center">
                    📋 Liste Officielle des Étudiants<br>
                    <strong>{{ $coursData->nom }}</strong>
                </td>
                <td class="footer-right">
                    {{ date('d/m/Y à H:i') }}<br>
                    <strong>Version numérique</strong>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>