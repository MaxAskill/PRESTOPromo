<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UpdateController;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (preg_match("/@([a-zA-Z0-9.-]+)$/", $request->email, $matches))
            $domain = $matches[1];

        if($domain == 'barbizonfashion.com') {
            $company = 1;
            $position = "Reviewer";
            $status = "Active";
        }
        else if ($domain == 'everydayproductscorp.com') {
            $company = 4;
            $position = "Reviewer";
            $status = "Active";
        }
        else {
            $company = null;
            $position = "User";
            $status = "Inactive";
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'position' => $position,
            'company' => $company,
            'status' => $status
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
        // $newRegister = new UpdateController;
        // $newRegister->deleteUserNotVerified($user->id);
    }
}
