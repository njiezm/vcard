<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Administration vCard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            border: none;
            color: #0f5132;
            animation: slideInDown 0.5s ease-out;
        }
        
        .alert-error {
            background: linear-gradient(135deg, #fccb90 0%, #ff9a9e 100%);
            border: none;
            color: #842029;
            animation: slideInDown 0.5s ease-out;
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
        
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 fade-in">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 bg-white rounded-full flex items-center justify-center shadow-2xl">
                    <i class="fas fa-id-card text-3xl text-purple-600"></i>
                </div>
                <h2 class="mt-6 text-3xl font-bold text-white">
                    Connexion Administration
                </h2>
                <p class="mt-2 text-sm text-white/80">
                    Accédez à votre espace de gestion vCard
                </p>
            </div>

            <!-- Login Form -->
            <div class="glass-effect rounded-2xl shadow-2xl p-8">
                @if(session('success'))
                    <div class="alert alert-success rounded-lg p-4 mb-6 flex items-center">
                        <i class="fas fa-check-circle mr-3 text-xl"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error rounded-lg p-4 mb-6 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-3 text-xl"></i>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error rounded-lg p-4 mb-6">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle mr-3 text-xl"></i>
                            <span class="font-medium">Veuillez corriger les erreurs suivantes :</span>
                        </div>
                        <ul class="list-disc list-inside ml-8">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('login.post') }}" method="POST">
                    @csrf
                    
                    <!-- Admin Code Field -->
                    <div>
                        <label for="admin_code" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-key mr-2"></i>Code d'administration
                        </label>
                        <input type="text" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200" 
                               id="admin_code" 
                               name="admin_code" 
                               value="{{ old('admin_code') }}"
                               required
                               placeholder="Entrez votre code d'administration">
                    </div>

                    <!-- Slug Field -->
                    <div>
                        <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-link mr-2"></i>Slug de votre vCard
                        </label>
                        <input type="text" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200" 
                               id="slug" 
                               name="slug" 
                               value="{{ old('slug') }}"
                               required
                               placeholder="exemple-john-doe">
                        <small class="text-gray-500 mt-1 block">
                            C'est l'identifiant unique de votre vCard
                        </small>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="btn-primary w-full text-white font-bold py-3 px-4 rounded-lg text-lg inline-flex items-center justify-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Se connecter
                        </button>
                    </div>
                </form>

                <!-- Help Text -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Votre code d'administration vous a été fourni lors de la création de votre vCard
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>