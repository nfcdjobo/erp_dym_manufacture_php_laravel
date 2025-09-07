{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <title>Inscription et Connexion</title>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideIn {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">

    <!-- Container principal -->
    <div class="w-full max-w-md" x-data="authForm()">

        <!-- Logo/Titre -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Bienvenue</h1>
            <p class="text-gray-600 mt-2" x-text="isLogin ? 'Connectez-vous à votre compte' : 'Créez votre compte'"></p>
        </div>

        <!-- Messages d'erreur et de succès -->
        <div x-show="message" class="mb-4 p-4 rounded-lg animate-slide-in"
             :class="messageType === 'error' ? 'bg-red-50 border border-red-200 text-red-700' : 'bg-green-50 border border-green-200 text-green-700'"
             x-transition.opacity>
            <div class="flex items-center">
                <svg x-show="messageType === 'error'" class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <svg x-show="messageType === 'success'" class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span x-text="message"></span>
            </div>
        </div>

        <!-- Card principale -->
        <div class="bg-white shadow-xl rounded-2xl p-8 animate-fade-in">

            <!-- Toggle buttons -->
            <div class="flex mb-6 bg-gray-100 rounded-lg p-1">
                <button @click="setMode(true)"
                        :class="isLogin ? 'bg-white shadow-sm text-gray-900' : 'text-gray-600'"
                        class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200">
                    Connexion
                </button>
                <button @click="setMode(false)"
                        :class="!isLogin ? 'bg-white shadow-sm text-gray-900' : 'text-gray-600'"
                        class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200">
                    Inscription
                </button>
            </div>

            <!-- Formulaire de connexion -->
            <form x-show="isLogin" @submit.prevent="submitForm" class="space-y-4" x-transition.opacity.duration.300ms>
                @csrf
                <div>
                    <label for="login-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="login-email" x-model="loginForm.email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="votre@email.com">
                </div>
                <div>
                    <label for="login-password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <input type="password" id="login-password" x-model="loginForm.password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="••••••••">
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">Se souvenir</span>
                    </label>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-500 transition duration-200">Mot de passe oublié ?</a>
                </div>
                <button type="submit" :disabled="loading"
                        class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-2 px-4 rounded-lg hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!loading">Se connecter</span>
                    <span x-show="loading" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Connexion...
                    </span>
                </button>
            </form>

            <!-- Formulaire d'inscription -->
            <form x-show="!isLogin" @submit.prevent="submitForm" class="space-y-4" x-transition.opacity.duration.300ms>
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="register-first-name" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                        <input type="text" id="register-first-name" x-model="registerForm.first_name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="John">
                    </div>
                    <div>
                        <label for="register-last-name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                        <input type="text" id="register-last-name" x-model="registerForm.last_name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Doe">
                    </div>
                </div>
                <div>
                    <label for="register-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="register-email" x-model="registerForm.email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="votre@email.com">
                </div>
                <div>
                    <label for="register-phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                    <input type="tel" id="register-phone" x-model="registerForm.phone" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="+33 6 12 34 56 78">
                </div>
                <div>
                    <label for="register-password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <input type="password" id="register-password" x-model="registerForm.password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="••••••••">
                    <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères</p>
                </div>
                <div class="flex items-start">
                    <input type="checkbox" required class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600">
                        J'accepte les <a href="#" class="text-blue-600 hover:text-blue-500">conditions d'utilisation</a>
                        et la <a href="#" class="text-blue-600 hover:text-blue-500">politique de confidentialité</a>
                    </span>
                </div>
                <button type="submit" :disabled="loading"
                        class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white py-2 px-4 rounded-lg hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!loading">Créer un compte</span>
                    <span x-show="loading" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Création...
                    </span>
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-gray-600 text-sm">
            <p>Besoin d'aide ? <a href="#" class="text-blue-600 hover:text-blue-500">Contactez le support</a></p>
        </div>
    </div>

    <script>
        function authForm() {
            return {
                isLogin: true,
                loading: false,
                message: '',
                messageType: '',
                loginForm: {
                    email: '',
                    password: ''
                },
                registerForm: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    phone: '',
                    password: ''
                },

                setMode(isLogin) {
                    this.isLogin = isLogin;
                    this.clearMessage();
                    this.clearForms();
                },

                clearMessage() {
                    this.message = '';
                    this.messageType = '';
                },

                clearForms() {
                    this.loginForm = { email: '', password: '' };
                    this.registerForm = { first_name: '', last_name: '', email: '', phone: '', password: '' };
                },

                showMessage(message, type = 'error') {
                    this.message = message;
                    this.messageType = type;
                    setTimeout(() => {
                        this.clearMessage();
                    }, 5000);
                },

                async submitForm() {
                    this.loading = true;
                    this.clearMessage();

                    try {
                        const formData = new FormData();
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                        let url = '';
                        if (this.isLogin) {
                            url = '{{ route("auth.login") }}';
                            formData.append('email', this.loginForm.email);
                            formData.append('password', this.loginForm.password);
                        } else {
                            url = '{{ route("auth.register") }}';
                            formData.append('first_name', this.registerForm.first_name);
                            formData.append('last_name', this.registerForm.last_name);
                            formData.append('email', this.registerForm.email);
                            formData.append('phone', this.registerForm.phone);
                            formData.append('password', this.registerForm.password);
                        }

                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });

                        if (!response.ok) {
                            throw new Error(`Erreur HTTP: ${response.status} ${response.statusText}`);
                        }

                        const responseText = await response.text();
                        console.log('Réponse brute du serveur:', responseText);

                        let data;
                        try {
                            data = JSON.parse(responseText);
                        } catch (parseError) {
                            console.error('Erreur de parsing JSON:', parseError);
                            throw new Error('La réponse du serveur n\'est pas un JSON valide.');
                        }

                        if (data.success) {
                            if (this.isLogin) {
                                this.showMessage('Connexion réussie ! Redirection...', 'success');
                                setTimeout(() => {
                                    window.location.href = '{{ route("dashboard") }}';
                                }, 2000);
                            } else {
                                this.showMessage('Compte créé avec succès ! Vous pouvez maintenant vous connecter.', 'success');
                                setTimeout(() => {
                                    this.setMode(true);
                                }, 2000);
                            }
                        } else {
                            let errorMessage = 'Une erreur est survenue. Veuillez réessayer.';

                            if (data.errors) {
                                if (Array.isArray(data.errors)) {
                                    errorMessage = data.errors.join(', ');
                                } else if (typeof data.errors === 'string') {
                                    errorMessage = data.errors;
                                }
                            }

                            this.showMessage(errorMessage, 'error');
                        }

                    } catch (error) {
                        console.error('Erreur complète:', error);
                        this.showMessage(`Erreur: ${error.message}`, 'error');
                    } finally {
                        this.loading = false;
                    }
                }
            };
        }
    </script>
</body>
</html>
