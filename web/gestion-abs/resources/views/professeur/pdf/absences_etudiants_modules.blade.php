<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Liste des absences par module</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 20px;
            color: #2c3e50;
            background-color: white;
        }

        .header-container {
            width: 100%;
            margin-bottom: 25px;
            border: 2px solid #0d6efd;
            border-radius: 10px;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .header-content td {
            padding: 10px;
            vertical-align: top;
        }

        .logo {
            width: 90px;
            border: 2px solid #0d6efd;
            border-radius: 8px;
            padding: 5px;
        }

        .main-title {
            font-size: 22pt;
            font-weight: bold;
            color: #0d6efd;
            text-align: center;
            margin-bottom: 10px;
        }

        .info-box {
            background: white;
            border: 1px solid #dee2e6;
            padding: 10px;
            border-radius: 6px;
            font-size: 9pt;
        }

        .stats-bar {
            margin: 15px 0;
            background: #e3f2fd;
            border: 2px solid #2196f3;
            border-radius: 8px;
            text-align: center;
            padding: 10px;
        }

        .students-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 9pt;
        }

        .students-table th,
        .students-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: center;
        }

        .students-table th {
            background-color: #0d6efd;
            color: white;
            font-size: 8pt;
        }

        .footer {
            margin-top: 30px;
            border-top: 3px solid #0d6efd;
            font-size: 8pt;
            text-align: center;
            color: #6c757d;
        }

        .signature-area {
            margin-top: 40px;
            text-align: right;
        }

        .signature-box {
            border: 2px dashed #dee2e6;
            width: 250px;
            height: 80px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            position: relative;
        }

        .signature-date {
            position: absolute;
            bottom: 5px;
            right: 10px;
            font-size: 8pt;
            color: #adb5bd;
        }
    </style>
</head>

<body>
    <!-- En-tête -->
    <div class="header-container">
        <table class="header-content" width="100%">
            <tr>
                <td width="25%">
                    <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo">
                </td>
                <td width="50%" style="text-align: center;">
                    <div class="main-title">Liste des absences par module</div>
                </td>
                <td width="25%">
                    <div class="info-box">
                        <div><strong>Professeur :</strong><br>{{ $professeur->user->name ?? '' }} {{ $professeur->user->prenom ?? '' }}</div>
                        <div><strong>Filière :</strong><br>{{ $filiereData->nom_filiere ?? '' }}</div>
                        <div><strong>Groupe :</strong><br>{{ $groupeData->nom_groupe ?? '' }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Statistiques -->
    <div class="stats-bar">
        <strong>{{ count($etudiants) }}</strong> Étudiants inscrits |
        <strong>{{ date('Y') }}</strong> Année académique |
        <strong>{{ date('d/m') }}</strong> Date d'édition
    </div>

    <!-- Tableau des absences -->
    <table class="students-table">
        <thead>
            <tr>
                <th rowspan="2">N°</th>
                <th rowspan="2">Nom</th>
                <th rowspan="2">Prénom</th>
                @foreach($modules as $module)
                <th colspan="2">{{ strtoupper($module->nom) }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($modules as $module)
                <th>Heures</th>
                <th>Séances</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
            function formatHeures($heures) {
            $totalMinutes = round(abs($heures) * 60);
            $h = intdiv($totalMinutes, 60);
            $m = $totalMinutes % 60;

            if ($m === 0) return "{$h}h";
            if ($h === 0) return "{$m}min";
            return "{$h}h{$m}min";
            }
            @endphp
            @foreach($etudiants as $etudiant)
            <tr>
                <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                <td style="text-transform: uppercase;">{{ $etudiant->user->name ?? '-' }}</td>
                <td style="text-transform: capitalize;">{{ $etudiant->user->prenom ?? '-' }}</td>
                @foreach ($modules as $module)
                @php
                $presences = $etudiant->presences ?? collect();
                $absences = $presences->filter(fn($p) => optional($p->session)->id_cours == $module->id && $p->etat === 0);
                $nbSeances = $absences->count();
                $nbHeures = $absences->sum(function ($p) {
                return $p->session ? \Carbon\Carbon::parse($p->session->heure_fin)->diffInMinutes(\Carbon\Carbon::parse($p->session->heure_debut)) / 60 : 0;
                });
                @endphp
                <td>{{ formatHeures($nbHeures) }}</td>
                <td>{{ $nbSeances }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Signature -->
    <div class="signature-area">
        <div class="signature-box">
            <div style="text-align: center;">ENS MARRAKECH</div>
            <div class="signature-date">{{ date('d/m/Y') }}</div>
        </div>
    </div>
    <!-- QR Code de vérification -->
    <div style="margin-top: 40px; padding: 15px; border: 2px dashed #ffc107; border-radius: 10px; background-color: #fff8e1;">
        <div style="text-align: center; font-weight: bold; font-size: 11pt; color: #856404; margin-bottom: 10px;">
            🔒 Vérification du document
        </div>
        <div style="text-align: center;">
            <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code de vérification" style="max-width: 120px;">
            <p style="font-size: 8pt; color: #856404; margin-top: 10px;">
                Scannez pour vérifier les métadonnées du générateur.
            </p>
        </div>
    </div>
    <!-- Pied de page -->
    <div class="footer">
        <p>📋 Liste Officielle des absences par module — absENS</p>
        <p>{{ date('d/m/Y à H:i') }} | Version numérique | Généré automatiquement</p>
    </div>
</body>

</html>