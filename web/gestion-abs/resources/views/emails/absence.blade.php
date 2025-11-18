<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Notification d'absence</title>
</head>

<body style="font-family: Inter, sans-serif; background-color: #f4f4f4; padding: 30px 10px; margin: 0;">
    <div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 12px; padding: 40px 30px; box-shadow: 0 0 10px rgba(0,0,0,0.05); text-align: center;">

        <!-- Logo -->
        <div style="margin-bottom: 20px;">
            <img src="{{ asset('images/logo.png') }}" alt="Logo absENS" style="width: 80px; height: auto;">
        </div>

        <!-- Titre -->
        <h2 style="color: #dc2626; font-size: 22px; margin-bottom: 10px;">🚨 Absence enregistrée</h2>
        <p style="color: #444; font-size: 15px;">Bonjour <strong>{{ strtoupper($user->prenom . ' ' . $user->name) }}</strong>,</p>
        <p style="color: #555; font-size: 14px;">Vous avez été marqué(e) <strong>absent(e)</strong> à la séance suivante :</p>

        <!-- Détails de l'absence -->
        <div style="background-color: #f9f9f9; padding: 20px; border-radius: 10px; margin: 20px 0; text-align: left;">
            <p style="margin: 8px 0;"><strong>📘 Cours :</strong> {{ $cours->nom }}</p>
            <p style="margin: 8px 0;"><strong>📅 Date :</strong> {{ $session->date }}</p>
            <p style="margin: 8px 0;"><strong>🕘 Heure :</strong> {{ $session->heure_debut }} - {{ $session->heure_fin }}</p>
        </div>

        <!-- Bouton -->
        <a href="{{ url('/etudiant/absences') }}" style="display: inline-block; background-color: #4f46e5; color: #fff; padding: 12px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 14px;">
            📂 Consulter mes absences
        </a>

        <p style="font-size: 14px; color: #555; margin-top: 20px;">📝 Merci de justifier votre absence si nécessaire.</p>

        <!-- Footer -->
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
        <p style="font-size: 12px; color: #999;">
            Ceci est un message automatique — merci de ne pas répondre.<br>
            &mdash; L’équipe absENS
        </p>
    </div>
</body>

</html>