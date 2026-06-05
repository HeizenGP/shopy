<?php

namespace App\Access\Presentation\Controllers;

use App\Access\Domain\Repositories\AuditLogRepositoryInterface;
use App\Access\Domain\Repositories\PermissionRepositoryInterface;
use App\Access\Domain\Repositories\RoleRepositoryInterface;
use App\Access\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(
        UserRepositoryInterface $users,
        RoleRepositoryInterface $roles,
        PermissionRepositoryInterface $permissions,
        AuditLogRepositoryInterface $auditLogs,
    ): View {
        return view('access.admin.dashboard.index', [
            'userCount' => $users->count(),
            'activeUserCount' => $users->activeCount(),
            'roleCount' => $roles->count(),
            'permissionCount' => $permissions->count(),
            'recentAuditLogs' => $auditLogs->recent(8),
        ]);
    }
}
