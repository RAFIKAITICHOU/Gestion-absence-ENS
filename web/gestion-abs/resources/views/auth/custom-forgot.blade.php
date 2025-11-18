<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>absENS - Mot de passe oublié</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --secondary-color: #7c3aed;
            --text-color: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --success-color: #10b981;
            --danger-color: #ef4444;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            color: var(--text-color);
            overflow-x: hidden;
        }

        .auth-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: url('/images/background.jpg') no-repeat center center;
            background-size: cover;
        }

        .auth-background::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }

        .auth-container {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            z-index: 1;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(25px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 460px;
            animation: slideInUp 0.8s ease-out;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .auth-card-body {
            padding: 3rem 2.5rem;
            position: relative;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 1.5rem;
            animation: pulse 2s ease-in-out infinite;
        }

        .logo img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        .welcome-text {
            font-size: 1.9rem;
            font-weight: 700;
            color: var(--text-color);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .welcome-subtitle {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 2.5rem;
            font-weight: 500;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 14px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            width: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, var(--primary-dark), #6d28d9);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .forgot-password:hover {
            color: var(--primary-dark);
        }

        .alert {
            border: none;
            border-radius: 14px;
            padding: 1.2rem 1.5rem;
            font-weight: 500;
            font-size: 0.9rem;
            backdrop-filter: blur(10px);
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
            border-left: 4px solid var(--success-color);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.15);
            color: #991b1b;
            border-left: 4px solid var(--danger-color);
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.2);
        }

        .form-floating>.form-control {
            border: 2px solid var(--border-color);
            border-radius: 14px;
            padding: 1.1rem 0.75rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: rgba(248, 250, 252, 0.8);
        }

        .form-floating>.form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            background-color: rgba(255, 255, 255, 0.95);
        }

        .invalid-feedback {
            font-size: 0.85rem;
            font-weight: 500;
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
            background-color: rgba(239, 68, 68, 0.05);
        }

        .form-control.is-invalid:focus {
            border-color: var(--danger-color);
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @media (max-width: 576px) {
            .auth-container {
                padding: 1rem;
            }

            .auth-card-body {
                padding: 2rem 1.5rem;
            }

            .welcome-text {
                font-size: 1.6rem;
            }

            .logo {
                width: 75px;
                height: 75px;
            }
        }
    </style>
</head>

<body>
    <!-- Arrière-plan -->
    <div class="auth-background"></div>

    <!-- Conteneur principal -->
    <div class="auth-container">
        <div class="auth-card fade-in">
            <div class="auth-card-body">
                <!-- Logo -->
                <div class="logo-container">
                    <div class="logo rounded-circle overflow-hidden p-2">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo absENS" />
                    </div>
                    <h1 class="welcome-text">absENS</h1>
                    <p class="welcome-subtitle">Plateforme de gestion des absences<br />Réinitialisation du mot de passe</p>
                </div>

                <!-- Alertes -->
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
                @endif

                <!-- Formulaire -->
                <form method="POST" action="{{ route('password.reset.custom') }}">
                    @csrf

                    <!-- Email -->
                    <div class="form-floating mb-4">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                            placeholder="Adresse e-mail" value="{{ old('email') }}" required />
                        <label for="email">Adresse e-mail</label>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date de naissance -->
                    <div class="form-floating mb-4">
                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date"
                            name="birth_date" placeholder="Date de naissance" value="{{ old('birth_date') }}" required />
                        <label for="birth_date">Date de naissance</label>
                        @error('birth_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bouton -->
                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="fas fa-envelope me-2"></i>
                        Envoyer un nouveau mot de passe
                    </button>

                    <!-- Lien retour -->
                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="forgot-password">
                            <i class="fas fa-arrow-left me-1"></i> Retour à la connexion
                        </a>
                    </div>
                    <div class="alert alert-info mt-4" role="alert" style="border-left: 4px solid #3b82f6; background-color: rgba(59, 130, 246, 0.12); color: #1e40af; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.15);">
                        <i class="fas fa-info-circle me-2"></i>
                        En cas de difficulté, veuillez <strong>contacter l'administration</strong> à
                        <a href="mailto:rafikaitichou@gmail.com" class="text-decoration-underline" style="color: #1e40af;">rafikaitichou@gmail.com</a>
                        ou vous rendre directement au bureau de l'administration durant les horaires d'ouverture.
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>