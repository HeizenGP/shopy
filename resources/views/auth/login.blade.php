<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Shopy</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS (temporary loaded via CDN for high fidelity demonstration if needed, or vanilla CSS. Let's write fully styled layout with embedded beautiful styles to guarantee it looks premium out of the box) -->
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
        }
        body {
            background-color: #030712;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            position: relative;
        }
        /* Background Glows */
        .ambient-glow-1 {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(0, 0, 0, 0) 70%);
            top: -100px;
            left: -100px;
            border-radius: 50%;
            z-index: 1;
            filter: blur(40px);
            animation: pulse-slow 8s infinite alternate;
        }
        .ambient-glow-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.12) 0%, rgba(0, 0, 0, 0) 70%);
            bottom: -150px;
            right: -100px;
            border-radius: 50%;
            z-index: 1;
            filter: blur(50px);
            animation: pulse-slow 12s infinite alternate-reverse;
        }
        @keyframes pulse-slow {
            0% { transform: scale(1); }
            100% { transform: scale(1.1) translate(30px, 30px); }
        }

        /* Glass Container */
        .login-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 2.5rem;
            border-radius: 24px;
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .login-card:hover {
            box-shadow: 0 25px 50px -12px rgba(99, 102, 241, 0.1);
        }

        /* Logo / Header */
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -0.05em;
            background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
            display: inline-block;
        }
        .subtitle {
            color: #9ca3af;
            font-size: 0.95rem;
            font-weight: 400;
        }

        /* Input Styles */
        .form-group {
            margin-bottom: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        label {
            color: #d1d5db;
            font-size: 0.875rem;
            font-weight: 500;
            letter-spacing: 0.025em;
        }
        input {
            width: 100%;
            padding: 0.875rem 1rem;
            border-radius: 12px;
            background: rgba(31, 41, 55, 0.5);
            border: 1px solid rgba(75, 85, 99, 0.4);
            color: #f9fafb;
            font-size: 1rem;
            outline: none;
            transition: all 0.2s ease;
        }
        input:focus {
            border-color: #6366f1;
            background: rgba(31, 41, 55, 0.8);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
        }

        /* Button Styles */
        .btn {
            width: 100%;
            padding: 0.875rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
            margin-top: 1rem;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.35);
        }
        .btn:active {
            transform: translateY(0);
        }

        /* Alerts */
        .alert {
            padding: 0.875rem;
            border-radius: 12px;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
        }

        /* Demo Hints */
        .demo-hint {
            margin-top: 1.5rem;
            padding: 0.875rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px dashed rgba(255, 255, 255, 0.1);
            font-size: 0.8rem;
            color: #9ca3af;
            line-height: 1.4;
        }
        .demo-hint strong {
            color: #6366f1;
        }
    </style>
</head>
<body class="antialiased">

    <!-- Background Decoration -->
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <!-- Login Container -->
    <div class="login-card">
        <div class="header">
            <div class="logo">Shopy.</div>
            <div class="subtitle">Ingresa a tu cuenta de administración</div>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="alert alert-success">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Alerts -->
        @if ($errors->any())
            <div class="alert alert-error">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="admin@shopy.com" required autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn">Iniciar Sesión</button>
        </form>

        <!-- Credentials for Demo -->
        <div class="demo-hint">
            💡 <strong>Prueba con la cuenta demo:</strong><br>
            Email: <code>test@example.com</code><br>
            Password: <code>password</code>
        </div>
    </div>

</body>
</html>
