<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des Professeurs</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 30px;
            color: #333;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header img {
            width: 80px;
        }

        .title-block {
            text-align: center;
            margin-bottom: 20px;
        }

        .title-block h2 {
            margin: 0;
            font-size: 18px;
            color: #0d6efd;
            border-bottom: 1px dashed #0d6efd;
            display: inline-block;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 25px;
        }

        th,
        td {
            border: 1px solid #bbb;
            padding: 8px;
            vertical-align: top;
        }

        th {
            background-color: #e9f2ff;
            text-align: center;
            color: #0d6efd;
        }

        ul {
            margin: 0;
            padding-left: 15px;
        }

        .footer {
            text-align: right;
            font-size: 10px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            color: #666;
        }

        .logo-text {
            font-size: 14px;
            font-weight: bold;
            color: #0d6efd;
            line-height: 1.2;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo">
        <div class="logo-text">
            Université Cadi Ayyad<br>
            ENS - Marrakech
        </div>
    </div>

    <div class="title-block">
        <h2>Liste des Professeurs</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Date de naissance</th>
                <th>Modules enseignés</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($professeurs as $index => $prof)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td>{{ $prof->user->name }}</td>
                <td>{{ $prof->user->prenom }}</td>
                <td>{{ $prof->user->email }}</td>
                <td>
                    {{ $prof->user->date_naissance ? \Carbon\Carbon::parse($prof->user->date_naissance)->format('d/m/Y') : '-' }}
                </td>
                <td>
                    <ul>
                        @foreach ($prof->coursSessions->pluck('cours.nom')->unique() as $module)
                        <li>{{ $module }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <footer>
        <div class="footer">
            Marrakech le : {{ $date->format('d/m/Y à H:i') }}
        </div>
        <div style="text-align: center; margin-top: 30px;">
            <img src="data:image/png;base64,{{ $qr }}" alt="QR Code" style="width: 90px;">
        </div>
    </footer>



</body>

</html>