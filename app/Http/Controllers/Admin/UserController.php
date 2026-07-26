<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->input('q'), fn ($query, $q) => $query->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $roles = [User::ROLE_STUDENT, User::ROLE_INSTRUCTOR, User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN];

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $actor = auth()->user();

        // A plain admin may not modify an existing admin/super-admin.
        if (! $actor->isSuperAdmin()) {
            abort_if($user->isAdmin(), 403);
        }

        $data = $request->validate([
            'role' => 'required|in:student,instructor,admin,super_admin',
        ]);

        // Only a super admin may grant elevated (admin/super-admin) roles.
        if (! $actor->isSuperAdmin() && in_array($data['role'], [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN], true)) {
            abort(403);
        }

        $user->update(['role' => $data['role']]);

        return back()->with('status', 'تم تحديث دور المستخدم.');
    }
}
