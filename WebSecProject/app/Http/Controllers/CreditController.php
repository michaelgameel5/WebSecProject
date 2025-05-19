<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        return view('credits.show', compact('user'));
    }

    private function abortIfNotEmployee()
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole('employee')) {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        if (!Auth::user()->can('manage_customers')) {
            abort(403, 'Unauthorized');
        }
        // Only customers have credit
        $users = User::role('customer')->get();
        return view('credits.index', compact('users'));
    }

    public function edit(User $user)
    {
        if (!Auth::user()->can('manage_customers')) {
            abort(403, 'Unauthorized');
        }
        return view('credits.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (!Auth::user()->can('manage_customers')) {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'credit' => 'required|numeric|min:0|max:10000',
        ]);
        $user->credit = $request->credit;
        $user->save();
        return redirect()->route('credits.index')
            ->with('success', 'Credit updated successfully for ' . $user->name);
    }

    public function add(Request $request, User $user)
    {
        if (!Auth::user()->can('manage_customers')) {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:10000',
        ]);
        $user->credit += $request->amount;
        $user->save();
        return redirect()->route('credits.index')
            ->with('success', 'Credit added successfully for ' . $user->name);
    }
} 