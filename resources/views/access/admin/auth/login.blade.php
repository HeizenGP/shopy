<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme-mode="light" data-theme-color="indigo">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login · Shopy Admin</title>
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
                    <h1 class="panel-title" style="font-size: 1.5rem;">Shopy Admin</h1>
                    <p style="color: var(--text-muted); margin-top: 0.5rem;">Ingresa con tu cuenta administrativa.</p>
                </div>

                <form method="POST" action="{{ route('admin.login.store') }}" style="display: grid; gap: 1rem;">
                    @csrf

                    <div>
                        <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.4rem;">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" autofocus required>
                        @error('email')
                            <p style="color: var(--danger-text); margin-top: 0.35rem; font-size: 0.875rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" style="display: block; font-weight: 600; margin-bottom: 0.4rem;">Contraseña</label>
                        <input id="password" type="password" name="password" autocomplete="current-password" required>
                        @error('password')
                            <p style="color: var(--danger-text); margin-top: 0.35rem; font-size: 0.875rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <label style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted);">
                        <input type="checkbox" name="remember" value="1" style="width: auto;">
                        Recordar sesión
                    </label>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">Ingresar</button>
                </form>
            </section>
        </main>
    </body>
</html>
