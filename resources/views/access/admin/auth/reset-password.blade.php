<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme-mode="light" data-theme-color="indigo">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Nueva contraseña · Shopy Admin</title>
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
            <section class="dashboard-content-panel" style="width: min(100%, 460px); margin: 0;">
                <div class="panel-header" style="display: block;">
                    <h1 class="panel-title" style="font-size: 1.5rem;">Nueva contraseña</h1>
                    <p style="color: var(--text-muted); margin-top: 0.5rem;">Define una nueva contraseña administrativa.</p>
                </div>

                <form method="POST" action="{{ route('admin.password.update') }}" style="display: grid; gap: 1rem;">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.4rem;">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $email) }}" autocomplete="email" required>
                        @error('email')
                            <p style="color: var(--danger-text); margin-top: 0.35rem; font-size: 0.875rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" style="display: block; font-weight: 600; margin-bottom: 0.4rem;">Contraseña</label>
                        <input id="password" type="password" name="password" autocomplete="new-password" required>
                        @error('password')
                            <p style="color: var(--danger-text); margin-top: 0.35rem; font-size: 0.875rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" style="display: block; font-weight: 600; margin-bottom: 0.4rem;">Confirmación</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">Actualizar contraseña</button>
                    <a href="{{ route('admin.login') }}" style="display: block; color: var(--primary); font-weight: 600; text-align: center;">Volver al login</a>
                </form>
            </section>
        </main>
    </body>
</html>
