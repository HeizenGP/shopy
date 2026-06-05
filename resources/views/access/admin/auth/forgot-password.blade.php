<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme-mode="light" data-theme-color="indigo">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Recuperar contraseña · Shopy Admin</title>
        <script>
            (function() {
                const mode = localStorage.getItem('admin-theme-mode') || 'light';
                const color = localStorage.getItem('admin-theme-color') || 'indigo';
                document.documentElement.setAttribute('data-theme-mode', mode);
                document.documentElement.setAttribute('data-theme-color', color);
            })();
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-body">
        <main style="min-height: 100vh; display: grid; place-items: center; padding: 1.5rem;">
            <section class="dashboard-content-panel" style="width: min(100%, 420px); margin: 0;">
                <div class="panel-header" style="display: block;">
                    <h1 class="panel-title" style="font-size: 1.5rem;">Recuperar contraseña</h1>
                    <p style="color: var(--text-muted); margin-top: 0.5rem;">Ingresa tu email administrativo.</p>
                </div>

                @if (session('status'))
                    <div class="badge badge-success" style="display: block; margin-bottom: 1rem; white-space: normal;">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.password.email') }}" style="display: grid; gap: 1rem;">
                    @csrf

                    <div>
                        <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.4rem;">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" autofocus required>
                        @error('email')
                            <p style="color: var(--danger-text); margin-top: 0.35rem; font-size: 0.875rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">Enviar enlace</button>
                    <a href="{{ route('admin.login') }}" style="display: block; color: var(--primary); font-weight: 600; text-align: center;">Volver al login</a>
                </form>
            </section>
        </main>
    </body>
</html>
