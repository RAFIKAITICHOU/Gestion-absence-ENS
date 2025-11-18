<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>absENS - Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('/assets/images/icon.png') }}" />
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
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 440px;
            position: relative;
            overflow: hidden;
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
            z-index: 1;
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
            color: white;
            font-size: 2.2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 32px rgba(79, 70, 229, 0.3);
            border: 3px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            animation: pulse 2s ease-in-out infinite;
        }

        .logo::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: logoShine 3s ease-in-out infinite;
        }

        .logo i {
            position: relative;
            z-index: 2;
        }

        .welcome-text {
            margin-bottom: 0.5rem;
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

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-floating>.form-control {
            border: 2px solid var(--border-color);
            border-radius: 14px;
            padding: 1.1rem 0.75rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: rgba(248, 250, 252, 0.8);
            backdrop-filter: blur(10px);
        }

        .form-floating>.form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            background-color: rgba(255, 255, 255, 0.95);
            transform: translateY(-1px);
        }

        .form-floating>label {
            color: var(--text-muted);
            font-weight: 500;
        }

        .form-check {
            margin-bottom: 1.5rem;
        }

        .form-check-input {
            border-radius: 6px;
            border: 2px solid var(--border-color);
            margin-top: 0.2rem;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-label {
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.9rem;
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
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
            background: linear-gradient(135deg, var(--primary-dark), #6d28d9);
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .forgot-password::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: width 0.3s ease;
        }

        .forgot-password:hover::after {
            width: 100%;
        }

        .forgot-password:hover {
            color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .alert {
            border: none;
            border-radius: 14px;
            padding: 1.2rem 1.5rem;
            margin-bottom: 1.5rem;
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

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            z-index: 10;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .form-floating>.form-control.with-icon {
            padding-left: 3.2rem;
        }

        .form-floating>.form-control.with-icon:focus~.input-icon {
            color: var(--primary-color);
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

        @keyframes logoShine {
            0% {
                transform: translateX(-100%) translateY(-100%) rotate(45deg);
            }

            50% {
                transform: translateX(100%) translateY(100%) rotate(45deg);
            }

            100% {
                transform: translateX(-100%) translateY(-100%) rotate(45deg);
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
                font-size: 1.8rem;
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
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
                <!-- Logo et titre -->
                <div class="logo-container">
                    <div class="logo rounded-circle overflow-hidden p-2" style="background: white;">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo absENS" style="width: 100%; height: auto;">
                    </div>
                    <h1 class="welcome-text">absENS</h1>
                    <p class="welcome-subtitle">Plateforme de gestion des absences <br> Connectez-vous à votre espace</p>
                </div>

                <!-- Messages d'alerte (cachés par défaut) -->
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="error-message">Message d'erreur</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>

                <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
                    <i class="fas fa-check-circle me-2"></i>
                    <span id="success-message">Message de succès</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>

                <!-- Formulaire -->
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <!-- Email -->
                    <div class="form-floating">
                        <div class="input-group">
                            <!-- <i class="fas fa-envelope input-icon"></i> -->
                            <input type="email"
                                class="form-control with-icon @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                placeholder="votre@email.com"
                                value="{{ old('email') }}"
                                required
                                autofocus>
                            <label for="email"></label>
                        </div>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div class="form-floating">
                        <div class="input-group">
                            <!-- <i class="fas fa-lock input-icon"></i> -->
                            <input type="password"
                                class="form-control with-icon @error('password') is-invalid @enderror"
                                id="password"
                                name="password"
                                placeholder="Mot de passe"
                                required>
                            <label for="password"></label>
                        </div>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Se souvenir de moi -->
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                        <label class="form-check-label" for="remember_me">
                            Se souvenir de moi
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="d-grid gap-3">
                        <button type="submit" class="btn btn-primary btn-login">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Se connecter
                        </button>

                        <div class="text-center">
                            <a href="{{ route('password.request.custom') }}" class="forgot-password">
                                <i class="fas fa-key me-1"></i>
                                Mot de passe oublié ?
                            </a>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation des champs au focus
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.parentElement.style.transform = 'scale(1.02)';
                this.parentElement.parentElement.style.transition = 'transform 0.2s ease';
            });

            input.addEventListener('blur', function() {
                this.parentElement.parentElement.style.transform = 'scale(1)';
            });
        });

        // Simulation de validation
        function showErrorMessage(message) {
            const errorAlert = document.querySelector('.alert-danger');
            const errorSpan = document.getElementById('error-message');
            errorSpan.textContent = message;
            errorAlert.style.display = 'block';

            setTimeout(() => {
                errorAlert.style.display = 'none';
            }, 5000);
        }

        function showSuccessMessage(message) {
            const successAlert = document.querySelector('.alert-success');
            const successSpan = document.getElementById('success-message');
            successSpan.textContent = message;
            successAlert.style.display = 'block';

            setTimeout(() => {
                successAlert.style.display = 'none';
            }, 5000);
        }
    </script>
</body>

</html>