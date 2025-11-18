<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des étudiants classés</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
        }

        .header img {
            width: 80px;
        }

        .header-title {
            text-align: right;
        }

        h2 {
            text-align: center;
            margin: 25px 0 10px;
        }

        .section {
            margin-top: 25px;
        }

        .filiere-title {
            background: #007bff;
            color: white;
            padding: 8px;
            font-weight: bold;
            border-radius: 4px;
        }

        .groupe-title {
            margin: 10px 0 5px;
            font-weight: bold;
            font-style: italic;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        .footer-date {
            text-align: right;
            font-size: 10px;
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo">
        <div class="header-title">
            <strong>AbsENS – ENS Marrakech</strong><br>
            <small>Liste générale des étudiants</small>
        </div>
    </div>

    <h2>Liste des étudiants</h2>

    <p class="footer-date">Marrakech, le {{ $date->format('d/m/Y à H:i') }}</p>

    @foreach($etudiantsParFiliereEtGroupe as $filiere => $groupes)
    <div class="section">
        <div class="filiere-title">Filière : {{ $filiere }}</div>

        @foreach($groupes as $groupe => $liste)
        <div class="groupe-title">Groupe : {{ $groupe }}</div>

        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>CNE</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Date de naissance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($liste as $index => $etudiant)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $etudiant->cne }}</td>
                    <td>{{ $etudiant->user->name }}</td>
                    <td>{{ $etudiant->user->prenom }}</td>
                    <td>{{ $etudiant->user->email }}</td>
                    <td>
                        {{ $etudiant->user->date_naissance ? \Carbon\Carbon::parse($etudiant->user->date_naissance)->format('d/m/Y') : '-' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach
    </div>
    @endforeach

</body>

</html>