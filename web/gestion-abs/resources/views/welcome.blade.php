<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Mon Site</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 text-center">
    <div class="h-screen flex flex-col justify-center items-center">
        <h1 class="text-4xl font-bold">Bienvenue sur Mon Site</h1>
        <p class="mt-4 text-lg">Connectez-vous pour accéder à votre espace.</p>
        <a href="{{ route('login') }}" class="mt-6 px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">Se connecter</a>
    </div>
</body>
</html>
