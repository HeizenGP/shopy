<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Shopy</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.12) 0%, rgba(0, 0, 0, 0) 70%);
            top: -150px;
            right: -100px;
            border-radius: 50%;
            z-index: 1;
            filter: blur(50px);
            animation: pulse-slow 10s infinite alternate;
        }
        .ambient-glow-2 {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.12) 0%, rgba(0, 0, 0, 0) 70%);
            bottom: -100px;
            left: -100px;
            border-radius: 50%;
            z-index: 1;
            filter: blur(40px);
            animation: pulse-slow 6s infinite alternate-reverse;
        }
        @keyframes pulse-slow {
            0% { transform: scale(1); }
            100% { transform: scale(1.1) translate(20px, 20px); }
        }

        /* Glass Container */
        .dashboard-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 540px;
            padding: 3rem;
            border-radius: 28px;
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            text-align: center;
        }

        /* Avatar Glow */
        .avatar-container {
            position: relative;
            width: 90px;
            height: 90px;
            margin: 0 auto 1.5rem auto;
            border-radius: 50%;
            background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.35);
        }
        .avatar-initials {
            font-size: 2.25rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.05em;
        }
        .status-dot {
            position: absolute;
            bottom: 3px;
            right: 3px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #10b981;
            border: 3px solid #111827;
        }

        /* Typography */
        h1 {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 0.25rem;
            color: #f9fafb;
        }
        .role {
            font-size: 0.875rem;
            font-weight: 600;
            color: #a855f7;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 2rem;
        }

        /* User Details Box */
        .details-box {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            text-align: left;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.95rem;
        }
        .detail-label {
            color: #9ca3af;
        }
        .detail-value {
            color: #f3f4f6;
            font-weight: 500;
        }

        /* Buttons */
        .btn-logout {
            width: 100%;
            padding: 0.875rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            color: #fca5a5;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.4);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="antialiased">

    <!-- Background Decoration -->
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <!-- Dashboard Container -->
    <div class="dashboard-card">
        <!-- Avatar -->
        <div class="avatar-container">
            <span class="avatar-initials">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
            </span>
            <div class="status-dot"></div>
        </div>

        <h1>¡Bienvenido, {{ Auth::user()->name }}!</h1>
        <div class="role">Administrador de la Tienda</div>

        <!-- Details -->
        <div class="details-box">
            <div class="detail-row">
                <span class="detail-label">ID de Usuario:</span>
                <span class="detail-value">#{{ Auth::user()->id }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Correo Electrónico:</span>
                <span class="detail-value">{{ Auth::user()->email }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Estado de la Cuenta:</span>
                <span class="detail-value" style="color: #10b981;">Activo</span>
            </div>
        </div>

        <!-- Logout Form -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">Cerrar Sesión</button>
        </form>
    </div>

</body>
</html>
