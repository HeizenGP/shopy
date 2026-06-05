<?php

namespace App\Access\Presentation\Controllers;

use App\Access\Application\UseCases\CreateUserUseCase;
use App\Access\Application\UseCases\DeleteUserUseCase;
use App\Access\Application\UseCases\ListUsersUseCase;
use App\Access\Application\UseCases\UpdateUserUseCase;
use App\Access\Domain\Repositories\RoleRepositoryInterface;
use App\Access\Domain\Repositories\UserRepositoryInterface;
use App\Access\Infrastructure\Models\UserModel;
use App\Access\Presentation\Requests\StoreUserRequest;
use App\Access\Presentation\Requests\UpdateUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request, ListUsersUseCase $users): View
    {
        $filters = [
            'search' => $request->string('search')->toString() ?: null,
            'is_active' => $request->filled('is_active') ? $request->boolean('is_active') : null,
        ];

        return view('access.admin.users.index', [
            'users' => $users->forAdmin($filters),
        ]);
    }

    public function create(RoleRepositoryInterface $roles): View
    {
        return view('access.admin.users.create', [
            'roles' => $roles->all(),
        ]);
    }

    public function store(StoreUserRequest $request, CreateUserUseCase $createUser): RedirectResponse
    {
        /** @var UserModel|null $actor */
        $actor = $request->user();
        $createUser->execute($request->toData(), $actor, $request->ip(), $request->userAgent());

        return redirect()
            ->route('admin.access.users.index')
            ->with('status', 'Usuario creado.');
    }

    public function edit(int $user, UserRepositoryInterface $users, RoleRepositoryInterface $roles): View
    {
        return view('access.admin.users.edit', [
            'user' => $users->find($user) ?? abort(404),
            'roles' => $roles->all(),
        ]);
    }

    public function update(UpdateUserRequest $request, int $user, UpdateUserUseCase $updateUser): RedirectResponse
    {
        /** @var UserModel|null $actor */
        $actor = $request->user();
        $updateUser->execute($request->toData(), $actor, $request->ip(), $request->userAgent());

        return redirect()
            ->route('admin.access.users.edit', $user)
            ->with('status', 'Usuario actualizado.');
    }

    public function destroy(Request $request, int $user, DeleteUserUseCase $deleteUser): RedirectResponse
    {
        /** @var UserModel|null $actor */
        $actor = $request->user();
        $deleteUser->execute($user, $actor, $request->ip(), $request->userAgent());

        return redirect()
            ->route('admin.access.users.index')
            ->with('status', 'Usuario eliminado.');
    }
}
