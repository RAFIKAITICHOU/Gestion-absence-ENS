<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Carte Étudiant - AbsENS</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            padding: 40px;
            margin: 0;
        }

        .card {
            width: 620px;
            background-color: #fff;
            margin: auto;
            padding: 25px 35px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            border: 1px solid #ddd;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img.logo {
            width: 70px;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 20px;
            color: #2c3e50;
            margin: 0;
        }

        .body {
            display: flex;
            align-items: flex-start;
            gap: 25px;
        }

        .photo {
            width: 110px;
            height: 140px;
            border-radius: 5px;
            background-color: #eee;
            border: 2px solid #ccc;
            object-fit: cover;
        }

        .photo-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
            font-size: 12px;
            height: 140px;
            text-align: center;
        }

        .info {
            flex: 1;
            font-size: 15px;
            color: #333;
        }

        .info p {
            margin: 6px 0;
        }

        .info strong {
            width: 100px;
            display: inline-block;
            color: #000;
        }

        .qr {
            text-align: center;
            margin-top: 100px;
        }

        .qr img {
            width: 170px;
        }

        .footer {
            text-align: right;
            margin-top: 20px;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>

<body>

    <div >
        <!-- En-tête -->
        <div class="header">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
            <h2>Carte Étudiant - AbsENS</h2>
        </div>

        <!-- Corps -->
        <div>
            @if($photoBase64)
            <img src="data:image/jpeg;base64,{{ $photoBase64 }}" alt="Photo de profil" class="photo">
            @else
            <div class="photo photo-placeholder">Pas de photo</div>
            @endif

            <div class="info">
                <p><strong>Nom :</strong> {{ $nom }}</p>
                <p><strong>Prénom :</strong> {{ $prenom }}</p>
                <p><strong>CNE :</strong> {{ $cne }}</p>
                <p><strong>Filière :</strong> {{ $filiere }}</p>
                <p><strong>Groupe :</strong> {{ $groupe }}</p>
            </div>
        </div>

        <!-- QR Code -->
        <div class="qr">
            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
        </div>

        <!-- Pied de page -->
        <div class="footer">
            Généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}
        </div>
    </div>

</body>

</html>