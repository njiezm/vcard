<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Phoenix vCard</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🔐</text></svg>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <style>
        :root {
            /* Phoenix Theme Colors */
            --phoenix-primary: #6366f1;
            --phoenix-primary-dark: #4f46e5;
            --phoenix-primary-light: #818cf8;
            --phoenix-secondary: #64748b;
            --phoenix-success: #10b981;
            --phoenix-warning: #f59e0b;
            --phoenix-danger: #ef4444;
            --phoenix-info: #06b6d4;
            
            /* Dark Theme */
            --phoenix-bg-primary: #0f172a;
            --phoenix-bg-secondary: #1e293b;
            --phoenix-bg-tertiary: #334155;
            --phoenix-surface: #1e293b;
            --phoenix-surface-hover: #334155;
            --phoenix-border: #334155;
            --phoenix-text-primary: #f1f5f9;
            --phoenix-text-secondary: #94a3b8;
            --phoenix-text-muted: #64748b;
            
            /* Gradients */
            --phoenix-gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --phoenix-gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --phoenix-gradient-dark: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            
            /* Shadows */
            --phoenix-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --phoenix-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --phoenix-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --phoenix-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --phoenix-shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --phoenix-shadow-glow: 0 0 20px rgba(99, 102, 241, 0.3);
        }

        [data-theme="light"] {
            --phoenix-bg-primary: #ffffff;
            --phoenix-bg-secondary: #f8fafc;
            --phoenix-bg-tertiary: #e2e8f0;
            --phoenix-surface: #ffffff;
            --phoenix-surface-hover: #f8fafc;
            --phoenix-border: #e2e8f0;
            --phoenix-text-primary: #0f172a;
            --phoenix-text-secondary: #334155;
            --phoenix-text-muted: #64748b;
            --phoenix-gradient-dark: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--phoenix-bg-primary);
            color: var(--phoenix-text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
            background: var(--phoenix-gradient-dark);
        }

        .bg-animation::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, var(--phoenix-primary) 0%, transparent 70%);
            opacity: 0.1;
            top: -200px;
            right: -200px;
            animation: float 20s infinite ease-in-out;
        }

        .bg-animation::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, var(--phoenix-primary-light) 0%, transparent 70%);
            opacity: 0.1;
            bottom: -150px;
            left: -150px;
            animation: float 15s infinite ease-in-out reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }

        /* Particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--phoenix-primary-light);
            border-radius: 50%;
            opacity: 0.3;
            animation: particle-float 20s infinite linear;
        }

        @keyframes particle-float {
            0% {
                transform: translateY(100vh) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 0.3;
            }
            90% {
                opacity: 0.3;
            }
            100% {
                transform: translateY(-100vh) translateX(100px);
                opacity: 0;
            }
        }

        /* Login Container */
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: var(--phoenix-surface);
            backdrop-filter: blur(10px);
            border: 1px solid var(--phoenix-border);
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--phoenix-shadow-xl), var(--phoenix-shadow-glow);
            position: relative;
            overflow: hidden;
            animation: slideInUp 0.6s ease;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--phoenix-gradient-primary);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo/Brand */
        .login-brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            background: var(--phoenix-gradient-primary);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 28px;
            color: white;
            box-shadow: var(--phoenix-shadow-lg);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .brand-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--phoenix-text-primary);
            margin-bottom: 8px;
        }

        .brand-subtitle {
            color: var(--phoenix-text-secondary);
            font-size: 14px;
        }

        /* Form Styles */
        .form-floating {
            position: relative;
            margin-bottom: 24px;
        }

        .form-control {
            background: var(--phoenix-bg-secondary);
            border: 2px solid var(--phoenix-border);
            border-radius: 12px;
            color: var(--phoenix-text-primary);
            font-size: 15px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            height: 50px;
        }

        .form-control:focus {
            background: var(--phoenix-bg-secondary);
            border-color: var(--phoenix-primary);
            color: var(--phoenix-text-primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--phoenix-text-muted);
        }

        .form-floating label {
            color: var(--phoenix-text-secondary);
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 500;
        }

        .form-floating .form-control:focus ~ label,
        .form-floating .form-control:not(:placeholder-shown) ~ label {
            color: var(--phoenix-primary);
            transform: translateY(-25px) scale(0.85);
            background: var(--phoenix-surface);
            padding: 0 8px;
            margin-left: 8px;
            margin-right: 8px;
        }

        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--phoenix-text-muted);
            cursor: pointer;
            z-index: 10;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--phoenix-primary);
        }

        /* Password Strength Indicator */
        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 8px;
            transition: all 0.3s ease;
            background: var(--phoenix-border);
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background: var(--phoenix-danger); width: 33%; }
        .strength-medium { background: var(--phoenix-warning); width: 66%; }
        .strength-strong { background: var(--phoenix-success); width: 100%; }

        /* Checkbox */
        .form-check {
            margin-bottom: 24px;
        }

        .form-check-input {
            background: var(--phoenix-bg-secondary);
            border: 2px solid var(--phoenix-border);
            border-radius: 6px;
            width: 20px;
            height: 20px;
            margin-top: 2px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background: var(--phoenix-primary);
            border-color: var(--phoenix-primary);
        }

        .form-check-label {
            color: var(--phoenix-text-secondary);
            font-size: 14px;
            margin-left: 8px;
            cursor: pointer;
            user-select: none;
        }

        /* Submit Button */
        .btn-phoenix {
            background: var(--phoenix-gradient-primary);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            padding: 14px 24px;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-phoenix::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-phoenix:hover::before {
            left: 100%;
        }

        .btn-phoenix:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        .btn-phoenix:active {
            transform: translateY(0);
        }

        .btn-phoenix .spinner-border {
            width: 20px;
            height: 20px;
            margin-left: 8px;
        }

        /* Links */
        .login-links {
            text-align: center;
            margin-top: 24px;
        }

        .login-link {
            color: var(--phoenix-primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .login-link:hover {
            color: var(--phoenix-primary-dark);
            transform: translateY(-1px);
        }

        /* Theme Toggle */
        .theme-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--phoenix-surface);
            border: 2px solid var(--phoenix-border);
            border-radius: 12px;
            padding: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 100;
        }

        .theme-toggle:hover {
            background: var(--phoenix-surface-hover);
            transform: scale(1.1);
        }

        .theme-toggle i {
            font-size: 20px;
            color: var(--phoenix-text-secondary);
        }

        /* Error Messages */
        .alert-phoenix {
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            border: none;
            font-size: 14px;
            animation: slideInDown 0.3s ease;
        }

        .alert-phoenix-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--phoenix-danger);
            border-left: 4px solid var(--phoenix-danger);
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 30px;
            color: var(--phoenix-text-muted);
            font-size: 13px;
        }

        .login-footer a {
            color: var(--phoenix-primary);
            text-decoration: none;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
                margin: 20px;
            }
            
            .brand-title {
                font-size: 20px;
            }
            
            .theme-toggle {
                top: 10px;
                right: 10px;
            }
        }

        /* Loading State */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            z-index: 1000;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid var(--phoenix-border);
            border-top-color: var(--phoenix-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation"></div>
    
    <!-- Particles -->
    <div class="particles" id="particles"></div>

    <!-- Theme Toggle -->
    <button class="theme-toggle" onclick="toggleTheme()" aria-label="Basculer le thème">
        <i class="fas fa-moon" id="themeIcon"></i>
    </button>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-card">
            <!-- Loading Overlay -->
            <div class="loading-overlay" id="loadingOverlay">
                <div class="loading-spinner"></div>
            </div>

            <!-- Brand -->
            <div class="login-brand">
                <div class="brand-logo">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h1 class="brand-title">Phoenix Admin</h1>
                <p class="brand-subtitle">Connectez-vous à votre espace</p>
            </div>

            <!-- Error Messages -->
            @if(session('error'))
                <div class="alert alert-phoenix-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('admin.login.post') }}" id="loginForm">
                @csrf
                
                <!-- Email Field -->
                <div class="form-floating mb-3">
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email" 
                           placeholder="name@example.com" 
                           required
                           autocomplete="email">
                    <label for="email">
                        <i class="fas fa-envelope me-2"></i>
                        Adresse email
                    </label>
                </div>

                <!-- Password Field -->
                <div class="form-floating mb-3 position-relative">
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           placeholder="Mot de passe" 
                           required
                           autocomplete="current-password">
                    <label for="password">
                        <i class="fas fa-lock me-2"></i>
                        Mot de passe
                    </label>
                    <button type="button" 
                            class="password-toggle" 
                            onclick="togglePassword()"
                            aria-label="Afficher/masquer le mot de passe">
                        <i class="fas fa-eye" id="passwordToggleIcon"></i>
                    </button>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-3">
                    <input class="form-check-input" 
                           type="checkbox" 
                           id="remember" 
                           name="remember">
                    <label class="form-check-label" for="remember">
                        Se souvenir de moi
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-phoenix" id="submitBtn">
                    <span id="btnText">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Se connecter
                    </span>
                    <span class="d-none" id="btnLoading">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        Connexion...
                    </span>
                </button>
            </form>

            <!-- Links -->
            <div class="login-links">
                <a href="#" class="login-link me-3">
                    <i class="fas fa-key me-1"></i>
                    Mot de passe oublié?
                </a>
                <a href="{{ route('home') }}" class="login-link">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour au site
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="login-footer">
            <p>&copy; {{ date('Y') }} Phoenix vCard. Tous droits réservés.</p>
            <p>
                Protégé par reCAPTCHA | 
                <a href="#">Confidentialité</a> | 
                <a href="#">Conditions</a>
            </p>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Initialize particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 20;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (15 + Math.random() * 10) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        // Theme toggle
        function toggleTheme() {
            const html = document.documentElement;
            const themeIcon = document.getElementById('themeIcon');
            
            if (html.getAttribute('data-theme') === 'dark') {
                html.setAttribute('data-theme', 'light');
                themeIcon.className = 'fas fa-sun';
                localStorage.setItem('theme', 'light');
            } else {
                html.setAttribute('data-theme', 'dark');
                themeIcon.className = 'fas fa-moon';
                localStorage.setItem('theme', 'dark');
            }
        }

        // Load saved theme
        function loadTheme() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            const html = document.documentElement;
            const themeIcon = document.getElementById('themeIcon');
            
            html.setAttribute('data-theme', savedTheme);
            themeIcon.className = savedTheme === 'dark' ? 'fas fa-moon' : 'fas fa-sun';
        }

        // Password toggle
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordToggleIcon = document.getElementById('passwordToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggleIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordToggleIcon.className = 'fas fa-eye';
            }
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('passwordStrengthBar');
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!]+/)) strength++;
            
            strengthBar.className = 'password-strength-bar';
            
            if (password.length > 0) {
                if (strength <= 2) {
                    strengthBar.classList.add('strength-weak');
                } else if (strength <= 4) {
                    strengthBar.classList.add('strength-medium');
                } else {
                    strengthBar.classList.add('strength-strong');
                }
            }
        }

        // Form validation
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Form submission
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');
            const loadingOverlay = document.getElementById('loadingOverlay');
            
            // Validation
            if (!validateEmail(email)) {
                showError('Veuillez entrer une adresse email valide');
                return;
            }
            
            if (password.length < 6) {
                showError('Le mot de passe doit contenir au moins 6 caractères');
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            loadingOverlay.classList.add('active');
            
            // Simulate API call
            setTimeout(() => {
                // Submit form for real
                this.submit();
            }, 1500);
        });

        // Show error message
        function showError(message) {
            const existingAlert = document.querySelector('.alert-phoenix-danger');
            if (existingAlert) {
                existingAlert.remove();
            }
            
            const alert = document.createElement('div');
            alert.className = 'alert alert-phoenix-danger alert-dismissible fade show';
            alert.innerHTML = `
                <i class="fas fa-exclamation-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            `;
            
            const form = document.getElementById('loginForm');
            form.parentNode.insertBefore(alert, form);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }

        // Input animations
        document.getElementById('email').addEventListener('input', function() {
            if (this.value.length > 0) {
                this.style.borderColor = 'var(--phoenix-primary)';
            } else {
                this.style.borderColor = 'var(--phoenix-border)';
            }
        });

        document.getElementById('password').addEventListener('input', function() {
            checkPasswordStrength(this.value);
            if (this.value.length > 0) {
                this.style.borderColor = 'var(--phoenix-primary)';
            } else {
                this.style.borderColor = 'var(--phoenix-border)';
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            loadTheme();
            
            // Add focus animations
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + Enter to submit
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                document.getElementById('loginForm').dispatchEvent(new Event('submit'));
            }
            
            // Escape to clear form
            if (e.key === 'Escape') {
                document.getElementById('loginForm').reset();
                document.getElementById('passwordStrengthBar').className = 'password-strength-bar';
            }
        });
    </script>
</body>
</html>