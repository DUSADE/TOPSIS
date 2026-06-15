<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SalesManagementController extends Controller
{
    public function index()
    {
        $sales = User::where('role', 'sales')->withCount('prospects')->get();
        return view('admin.sales.index', compact('sales'));
    }

    public function create()
    {
        return view('admin.sales.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'sales',
        ]);

        return redirect()->route('admin.sales.index')->with('success', 'Akun Sales berhasil dibuat.');
    }

    public function edit(User $user)
    {
        if ($user->role !== 'sales') {
            abort(403);
        }
        return view('admin.sales.edit', compact('user'));
    }   

    public function update(Request $request, User $user)
    {
        if ($user->role !== 'sales') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if ($validated['password']) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return redirect()->route('admin.sales.index')->with('success', 'Data Sales berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->role !== 'sales') {
            abort(403);
        }

        $request->validate([
            'transfer_to' => 'required|exists:users,id',
        ]);

        $targetUserId = $request->transfer_to;

        if ($targetUserId == $user->id) {
            return back()->with('error', 'Tidak bisa mengalihkan data ke user yang akan dihapus.');
        }

        // Reassign prospects
        Prospect::where('user_id', $user->id)->update(['user_id' => $targetUserId]);

        // Delete the user
        $user->delete();

        return redirect()->route('admin.sales.index')->with('success', 'Sales berhasil dihapus dan data dialihkan.');
    }
}
