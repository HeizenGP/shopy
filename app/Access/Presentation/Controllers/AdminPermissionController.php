<?php

namespace App\Access\Presentation\Controllers;

use App\Access\Application\UseCases\ListPermissionsUseCase;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class AdminPermissionController extends Controller
{
    public function index(Request $request, ListPermissionsUseCase $permissions): View
    {
        return view('access.admin.permissions.index', [
            'permissions' => $permissions->forAdmin([
                'search' => $request->string('search')->toString() ?: null,
                'module' => $request->string('module')->toString() ?: null,
            ]),
            'permissionsByModule' => $permissions->groupedByModule(),
        ]);
    }
}
