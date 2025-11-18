<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Absences de {{ $etudiant->user->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            margin-bottom: 10px;
        }

        .warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            padding: 10px;
            margin-top: 15px;
            text-align: center;
        }

        .info {
            background-color: #e2f0d9;
            color: #2e6f1e;
            border: 1px solid #b6df9b;
            padding: 10px;
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
        <h2>Fiche d'absences - {{ $etudiant->user->prenom }} {{ $etudiant->user->name }}</h2>
        <p><strong>Filière :</strong> {{ $etudiant->groupe->filiere->nom_filiere ?? '-' }}</p>
        <p><strong>Groupe :</strong> {{ $etudiant->groupe->nom_groupe ?? '-' }}</p>
        <p><strong>Date d'export :</strong> {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    @php
    $absences = $presences->where('etat', 0);
    $retards = $presences->where('etat', 1)->whereNotNull('retard');
    @endphp

    @if($absences->count() > 0)
    <div class="warning">
        ⚠️ Vous avez des absences enregistrées. <br>
        Si le total dépasse 20% des heures d’un module, vous ne pourrez pas passer l’examen final.
    </div>

    <table>
        <thead>
            <tr>
                <th>Cours</th>
                <th>Date</th>
                <th>Heure</th>
                <th>État</th>
                <th>Justification</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absences as $p)
            <tr>
                <td>{{ $p->session->cours->nom ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($p->session->date)->format('d/m/Y') }}</td>
                <td>{{ $p->session->heure_debut }} - {{ $p->session->heure_fin }}</td>
                <td>Absent</td>
                <td>{{ $p->justification ? 'Justifiée' : 'Non justifiée' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif


    <!-- ////////// j'ai ajouter rafik ichou ////////// -->
    @php
    $retards = $presences->filter(fn($p) => $p->etat === 1 && $p->retard > 0);
    @endphp


    @if($retards->count() > 0)
    <div class="warning" style="background-color:#fffbe6; border-color:#ffe58f;">
        ⚠️ Vous avez été en retard lors de certaines séances.
    </div>

    <table>
        <thead>
            <tr>
                <th>Cours</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Retard</th>
            </tr>
        </thead>
        <tbody>
            @foreach($retards as $p)
            <tr>
                <td>{{ $p->session->cours->nom ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($p->session->date)->format('d/m/Y') }}</td>
                <td>{{ $p->session->heure_debut }} - {{ $p->session->heure_fin }}</td>
                <td>{{ $p->retard }} min</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($absences->isEmpty() && $retards->isEmpty())
    <div class="info">
        Félicitations, aucune absence ni retard enregistrés.
    </div>
    @endif

    <div style="margin-top: 30px; text-align: center;">
        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
    </div>


</body>

</html>