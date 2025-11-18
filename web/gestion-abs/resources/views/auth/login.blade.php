<x-guest-layout>
    <!-- Logo et titre -->
    <div class="logo-container">
        <div class="logo">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h1 class="welcome-text">EduConnect</h1>
        <p class="welcome-subtitle">Plateforme éducative - Connectez-vous à votre espace</p>
    </div>

    <!-- Message d'erreur de session expirée ou autre -->
    @if ($errors->has('message'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ $errors->first('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
    @endif

    <!-- Message succès (ex: mot de passe réinitialisé) -->
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
    @endif

    <!-- Formulaire -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="form-floating">
            <div class="input-group">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email"
                    class="form-control with-icon @error('email') is-invalid @enderror"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="votre@email.com"
                    required
                    autofocus>
                <label for="email">Adresse email</label>
            </div>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Mot de passe -->
        <div class="form-floating">
            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password"
                    class="form-control with-icon @error('password') is-invalid @enderror"
                    id="password"
                    name="password"
                    placeholder="Mot de passe"
                    required>
                <label for="password">Mot de passe</label>
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
                <!-- @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-password">
                    <i class="fas fa-key me-1"></i>
                    Mot de passe oublié ?
                </a>
                @endif -->
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-password">
                    <i class="fas fa-key me-1"></i>
                    Mot de passe oublié ?
                </a>
                @endif
            </div>
        </div>
    </form>

    <style>
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

        .welcome-text {
            margin-bottom: 0.5rem;
            font-size: 1.9rem;
            font-weight: 700;
            color: #1e293b;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .welcome-subtitle {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 2.5rem;
            font-weight: 500;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-floating>.form-control {
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 1.1rem 0.75rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: rgba(248, 250, 252, 0.8);
            backdrop-filter: blur(10px);
        }

        .form-floating>.form-control:focus {
            border-color: #4f46e5;
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
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
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
            background: linear-gradient(135deg, #4338ca, #6d28d9);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .forgot-password {
            color: #4f46e5;
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
            background: #4f46e5;
            transition: width 0.3s ease;
        }

        .forgot-password:hover::after {
            width: 100%;
        }

        .forgot-password:hover {
            color: #4338ca;
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
            border-left: 4px solid #10b981;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.15);
            color: #991b1b;
            border-left: 4px solid #ef4444;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.2);
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
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            z-index: 10;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .form-floating>.form-control.with-icon {
            padding-left: 3rem;
        }

        .form-floating>.form-control.with-icon:focus+.input-icon {
            color: #4f46e5;
        }

        @media (max-width: 576px) {
            .welcome-text {
                font-size: 1.6rem;
            }

            .logo {
                width: 75px;
                height: 75px;
                font-size: 1.8rem;
            }
        }
    </style>

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
    </script>
</x-guest-layout>