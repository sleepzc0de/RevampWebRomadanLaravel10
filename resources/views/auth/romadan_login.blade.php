<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kementerian Keuangan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        .login-container {
            min-height: 100vh;
            background: linear-gradient(to right, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.3)), url('/storage/romadan_gambar_web/{{$gambar->image ?? ''}}');
            background-size: cover;
            background-position: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 480px;
            width: 90%;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-control {
            width: 100%;
            padding: 12px 16px;
            padding-left: 45px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 1rem;
            background: #f8fafc;
        }

        .input-control:focus {
            border-color: #1e88e5;
            background: white;
            box-shadow: 0 0 0 4px rgba(30, 136, 229, 0.1);
            outline: none;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        .btn-login {
            background: #1e88e5;
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            width: 100%;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover {
            background: #1976d2;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 136, 229, 0.2);
        }

        .logo-kemenkeu {
            width: 400px;
            height: 64px;
            object-fit: contain;
        }

        .title-section {
            margin-bottom: 2rem;
            text-align: center;
        }

        .footer-text {
            color: #64748b;
            font-size: 0.875rem;
            text-align: center;
            margin-top: 1.5rem;
        }

        .footer-link {
            color: #1e88e5;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-link:hover {
            color: #1976d2;
            text-decoration: underline;
        }
        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .error-toast {
            position: fixed;
            top: 24px;
            right: 24px;
            background: white;
            border-left: 4px solid #ef4444;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 16px;
            animation: slideInDown 0.5s ease-out;
            z-index: 50;
            max-width: 400px;
        }

        .error-toast-close {
            position: absolute;
            top: 12px;
            right: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .error-toast-close:hover {
            transform: scale(1.1);
        }

        .input-group {
            position: relative;
        }

        .input-error {
            border-color: #ef4444;
            background-color: #fef2f2;
            transition: all 0.3s ease;
        }

        .input-error:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .error-message {
            font-size: 0.875rem;
            color: #ef4444;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 6px;
            animation: fadeIn 0.3s ease-out;
        }

        .error-shake {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .input-icon-error {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #ef4444;
            animation: fadeIn 0.3s ease-out;
        }
    </style>
     <script>
        function closeToast() {
            const toast = document.querySelector('.error-toast');
            if (toast) {
                toast.style.animation = 'fadeOut 0.3s ease-out forwards';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }

        function shakeForm() {
            const form = document.querySelector('form');
            form.classList.add('error-shake');
            setTimeout(() => {
                form.classList.remove('error-shake');
            }, 500);
        }
    </script>
</head>
<body>
    <div class="login-container flex items-center justify-center p-4">
        @if ($errors->any())
        <div class="error-toast" role="alert">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900">Terjadi kesalahan</h3>
                    <div class="mt-2 text-sm text-gray-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <button onclick="closeToast()" class="error-toast-close">
                <svg class="w-4 h-4 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <script>shakeForm();</script>
        @endif

        <div class="login-card p-8">
            <div class="title-section">
                <img src="/webromadan/fe/images/romadan/logo_3.png" alt="Logo Kemenkeu" class="logo-kemenkeu mx-auto mb-4">
            </div>


            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div class="input-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <span class="input-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </span>
                        <input type="email"
                               name="email"
                               class="input-control @error('email') input-error @enderror"
                               placeholder="Masukkan Email"
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                        <span class="input-icon-error">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        @enderror
                    </div>
                    @error('email')
                        <p class="error-message">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="input-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <span class="input-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </span>
                        <input type="password"
                               name="password"
                               class="input-control @error('password') input-error @enderror"
                               placeholder="Masukkan Password"
                               required>
                        @error('password')
                        <span class="input-icon-error">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        @enderror
                    </div>
                    @error('password')
                        <p class="error-message">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="input-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">CAPTCHA</label>
                    <div class="flex items-center space-x-4 bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-100">
                        <div class="flex-1">
                            <span class="text-lg font-semibold text-gray-700">
                                {{ app(App\Services\CaptchaService::class)->createCaptcha() }}
                            </span>
                            <p class="text-sm text-gray-500 mt-1">Silakan selesaikan perhitungan di atas</p>
                        </div>
                        <button type="button"
                                class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-full transition-colors duration-200"
                                onclick="window.location.reload()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                    </div>
                    <input type="text"
                           name="captcha"
                           class="input-control @error('captcha') input-error @enderror mt-2"
                           placeholder="Masukkan jawaban Anda"
                           required>
                    @error('captcha')
                        <p class="error-message">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit" class="btn-login">
                    <span>Let's Go!</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </button>
            </form>

            <div class="footer-text">
                <p>&copy; 2025 <a href="http://www.romadan.kemenkeu.go.id/" class="footer-link">Biro Manajemen BMN dan Pengadaan</a></p>
                <p>Powered by Romadan</p>
            </div>
        </div>
    </div>
</body>
</html>
