<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des étudiants</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 40px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #444;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            height: 60px;
        }

        h2 {
            margin: 0;
            font-size: 18px;
        }

        .info {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .small {
            font-size: 11px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
        <h2>Liste des étudiants</h2>
    </div>

    <div class="info">
        <strong>Module :</strong> {{ $cours->nom }}<br>
        <strong>Professeur :</strong> {{ Auth::user()->prenom }} {{ Auth::user()->name }}<br>
        <strong>Filière :</strong> {{ $groupe->filiere->nom_filiere }}<br>
        <strong>Groupe :</strong> {{ $groupe->nom_groupe }}
    </div>

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>CNE</th>
                <th>Nom</th>
                <th>Prénom</th>
            </tr>
        </thead>
        <tbody>
            @php
            $etudiants = $etudiants->sortBy([
            ['user.name', 'asc'],
            ['user.prenom', 'asc']
            ]);
            @endphp
            @foreach($etudiants as $index => $etudiant)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $etudiant->cne }}</td>
                <td>{{ $etudiant->user->name }}</td>
                <td>{{ $etudiant->user->prenom }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>