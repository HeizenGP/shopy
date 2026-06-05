<x-layouts.admin title="Dashboard">
    <div class="page-header-block">
        <div class="page-title-area">
            <h1>Dashboard</h1>
            <p>Resumen operativo del módulo Access y eventos sensibles recientes.</p>
        </div>
    </div>

    <div class="stats-cards-grid">
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128A9.38 9.38 0 0017.625 19.5 9.337 9.337 0 0021.746 18.548 4.125 4.125 0 0014.213 16.055M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21 12.318 12.318 0 012.25 19.234l-.001-.109a6.375 6.375 0 0111.964-3.07M12 7.5a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z" />
                </svg>
            </div>
            <div class="stat-card-details">
                <span class="stat-card-value">{{ $userCount }}</span>
                <span class="stat-card-label">Usuarios</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon" style="background-color: var(--success-bg); color: var(--success-text);">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-card-details">
                <span class="stat-card-value">{{ $activeUserCount }}</span>
                <span class="stat-card-label">Activos</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon" style="background-color: var(--info-bg); color: var(--info-text);">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0M3.75 6H7.5m3 6h9.75m-9.75 0a1.5 1.5 0 11-3 0m-3.75 0H7.5m3 6h9.75m-9.75 0a1.5 1.5 0 11-3 0m-3.75 0H7.5" />
                </svg>
            </div>
            <div class="stat-card-details">
                <span class="stat-card-value">{{ $roleCount }}</span>
                <span class="stat-card-label">Roles</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon" style="background-color: var(--warning-bg); color: var(--warning-text);">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>
            <div class="stat-card-details">
                <span class="stat-card-value">{{ $permissionCount }}</span>
                <span class="stat-card-label">Permisos</span>
            </div>
        </div>
    </div>

    <div class="dashboard-content-panel">
        <div class="panel-header">
            <h2 class="panel-title">Auditoría reciente</h2>
        </div>

        <div class="table-responsive-wrapper">
            <table class="admin-datatable">
                <thead>
                    <tr>
                        <th>Evento</th>
                        <th>Usuario</th>
                        <th>IP</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentAuditLogs as $log)
                        <tr>
                            <td><span class="badge badge-info">{{ $log->event }}</span></td>
                            <td>{{ $log->user?->email ?? 'Sistema' }}</td>
                            <td>{{ $log->ip_address ?? 'N/D' }}</td>
                            <td>{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-muted);">Sin eventos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
