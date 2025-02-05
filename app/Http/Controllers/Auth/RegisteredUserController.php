<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'account_type' => ['required', 'in:candidate,company'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->account_type
        ]);

        event(new Registered($user));

        Auth::login($user);

        if (Auth::user()->role === 'company') {
//            Company::create([
//                'user_id' => $user->id,
//                'name' => $user->name,
//                'slug' => str_replace(' ', '-', strtolower($user->name)),
//            ]);
            return redirect(RouteServiceProvider::COMPANY_DASHBOARD);
        } elseif (Auth::user()->role === 'candidate') {
            // Handle create candidate
            return redirect(RouteServiceProvider::CANDIDATE_DASHBOARD);
        }
        return redirect('/');
    }
}
