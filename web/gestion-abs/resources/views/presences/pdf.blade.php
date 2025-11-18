<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('/assets/images/icon.png') }}" />
    <title>Liste des présences</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header img {
            height: 200px;
        }

        h2,
        p {
            text-align: center;
            margin: 2px 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .qr-section {
            margin-top: 40px;
            width: 100%;
        }

        .qr-section td {
            vertical-align: top;
            padding-top: 20px;
        }

        .qr-code {
            height: 100px;
        }

        .cachet {
            height: 80px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('images/logoPDF.png') }}" alt="Logo">
    </div>

    <h2>Liste des présences</h2>

    <p>
        Filière :
        {{ $filieres->where('id', request('filiere_id'))->first()?->nom_filiere ?? '—' }} |
        Groupe :
        {{ $groupes->where('id', request('groupe_id'))->first()?->nom_groupe ?? '—' }}
    </p>

    <p>
        Type de filtrage : <strong>{{ $filtrageText }}</strong>
        @if(request('date'))
        | Date ciblée : <strong>{{ request('date') }}</strong>
        @endif
    </p>

    <p>
        Date d’exportation : <strong>{{ now()->format('Y-m-d H:i') }}</strong>
    </p>

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Nom complet</th>
                <th>Date</th>
                <th>Cours</th>
                <th>Salle</th>
                <th>État</th>
                <th>Retard</th>
            </tr>
        </thead>
        <tbody>
            @php $row = 1; @endphp
            @foreach($etudiants as $etudiant)
            @php
            $presences = $etudiant->presences->filter(function ($presence) {
            if (request('date') && optional($presence->session)->date !== request('date')) {
            return false;
            }

            if ($presence->etat == 0) {
            if (request('non_justifiees')) {
            return $presence->justification == null;
            }
            return true;
            }

            return $presence->etat == 1 && $presence->retard; //Inclure les retards
            });

            $presencesCount = $presences->count();
            $first = true;
            @endphp

            @if($presencesCount > 0)
            @foreach($presences as $presence)
            <tr>
                @if($first)
                <td rowspan="{{ $presencesCount }}">{{ $row++ }}</td>
                <td rowspan="{{ $presencesCount }}">{{ $etudiant->user->prenom }} {{ $etudiant->user->name }}</td>
                @php $first = false; @endphp
                @endif
                <td>{{ $presence->session->date ?? '—' }}</td>
                <td>{{ $presence->session->cours->nom ?? '—' }}</td>
                <td>{{ $presence->session->salle->nom ?? '—' }}</td>
                <td>
                    @if($presence->etat === 0)
                    {{ $presence->justification ? 'Absent justifié' : 'Absent non justifié' }}
                    @else
                    Présent
                    @endif
                </td>
                <td>
                    @if($presence->etat === 1 && $presence->retard)
                    {{ $presence->retard }} min
                    @else
                    —
                    @endif
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td>{{ $row++ }}</td>
                <td>{{ $etudiant->user->prenom }} {{ $etudiant->user->name }}</td>
                <td colspan="5" class="text-muted">Aucune absence et retard enregistrée</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    <br><br>

    <table class="qr-section">
        <tr>
            <td style="text-align: center;">
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" class="qr-code">
            </td>
            <td style="text-align: right;">
                <img src="{{ public_path('images/logo.png') }}" alt="Cachet" class="cachet"><br>
                Fait à Marrakech, le {{ now()->format('d/m/Y') }}<br>
                <strong>Le service de gestion des absences - ENS</strong>
            </td>
        </tr>
    </table>

</body>

</html>