<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('/assets/images/icon.png') }}" />
    <title>Votre mot de passe a été réinitialisé</title>
</head>

<body style="font-family: Inter, sans-serif; background-color: #f4f4f4; padding: 30px 10px; margin: 0;">
    <div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 12px; padding: 40px 30px; box-shadow: 0 0 10px rgba(0,0,0,0.05); text-align: center;">

        <!-- Logo -->
        <div style="margin-bottom: 20px;">
            <img src="images/logo.png" alt="Logo absENS" style="width: 80px; height: auto;">
        </div>

        <!-- Titre -->
        <h2 style="color: #4f46e5; font-size: 20px; margin-bottom: 10px;">Votre mot de passe a été réinitialisé</h2>
        <p style="color: #555; font-size: 14px;">Veuillez utiliser ce mot de passe temporaire pour vous connecter :</p>

        <!-- Mot de passe -->
        <div style="display: inline-block; background-color: #e6f4ea; color: #065f46; padding: 12px 24px; border-radius: 8px; font-size: 16px; font-weight: bold; margin: 20px 0;">
            {{ $password }}
        </div>

        <p style="font-size: 14px; color: #555;">Merci de le modifier dès votre prochaine connexion.</p>

        <!-- Footer -->
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
        <p style="font-size: 12px; color: #999;">
            Merci de ne pas répondre à ce message automatique.<br>
            &mdash; L’équipe absENS
        </p>
    </div>
</body>

</html>