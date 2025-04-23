<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\AdminUserUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function index(): Response
    {
        $users = User::all(); 
        return Inertia::render('Admin/Index', [
            'users' => $users,
        ]);
    }

    public function edit(User $user): Response
    {
        return Inertia::render('Admin/Edit', [
            'user' => $user,
        ]);
    }

    public function update(AdminUserUpdateRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());
        return redirect()->route('admin.index')
                         ->with('success', 'Felhasználó frissítve.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('admin.index')
                         ->with('success', 'Felhasználó törölve.');
    }
}
