<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'company_name' => ['required', 'string', 'max:255'],
            'currency' => ['nullable', 'string', 'size:3'],
            'timezone' => ['nullable', 'string'],
        ]);

        $company = Company::create([
            'name' => $data['company_name'],
            'currency' => $data['currency'] ?? 'RON',
            'timezone' => $data['timezone'] ?? 'Europe/Bucharest',
            'settings' => [
                'working_hours' => $request->input('working_hours'),
                'positions' => $request->input('positions') ? array_filter(array_map('trim', explode(',', $request->input('positions')))) : [],
            ],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'company_id' => $company->id,
            'role' => 'owner',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
