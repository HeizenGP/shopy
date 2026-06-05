<?php

namespace App\Access\Presentation\Controllers;

use App\Access\Application\UseCases\CreateRoleUseCase;
use App\Access\Application\UseCases\DeleteRoleUseCase;
use App\Access\Application\UseCases\ListRolesUseCase;
use App\Access\Application\UseCases\UpdateRoleUseCase;
use App\Access\Domain\Repositories\PermissionRepositoryInterface;
use App\Access\Domain\Repositories\RoleRepositoryInterface;
use App\Access\Infrastructure\Models\UserModel;
use App\Access\Presentation\Requests\StoreRoleRequest;
use App\Access\Presentation\Requests\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class AdminRoleController extends Controller
{
    public function index(Request $request, ListRolesUseCase $roles): View
    {
        return view('access.admin.roles.index', [
            'roles' => $roles->forAdmin([
                'search' => $request->string('search')->toString() ?: null,
            ]),
        ]);
    }

    public function create(PermissionRepositoryInterface $permissions): View
    {
        return view('access.admin.roles.create', [
            'permissionsByModule' => $permissions->groupedByModule(),
        ]);
    }

    public function store(StoreRoleRequest $request, CreateRoleUseCase $createRole): RedirectResponse
    {
        /** @var UserModel|null $actor */
        $actor = $request->user();
        $role = $createRole->execute($request->toData(), $actor, $request->ip(), $request->userAgent());

        return redirect()
            ->route('admin.access.roles.edit', $role)
            ->with('status', 'Rol creado.');
    }

    public function edit(int $role, RoleRepositoryInterface $roles, PermissionRepositoryInterface $permissions): View
    {
        return view('access.admin.roles.edit', [
            'role' => $roles->find($role) ?? abort(404),
            'permissionsByModule' => $permissions->groupedByModule(),
        ]);
    }

    public function update(UpdateRoleRequest $request, int $role, UpdateRoleUseCase $updateRole): RedirectResponse
    {
        /** @var UserModel|null $actor */
        $actor = $request->user();
        $updateRole->execute($request->toData(), $actor, $request->ip(), $request->userAgent());

        return redirect()
            ->route('admin.access.roles.edit', $role)
            ->with('status', 'Rol actualizado.');
    }

    public function destroy(Request $request, int $role, DeleteRoleUseCase $deleteRole): RedirectResponse
    {
        /** @var UserModel|null $actor */
        $actor = $request->user();
        $deleteRole->execute($role, $actor, $request->ip(), $request->userAgent());

        return redirect()
            ->route('admin.access.roles.index')
            ->with('status', 'Rol eliminado.');
    }
}
