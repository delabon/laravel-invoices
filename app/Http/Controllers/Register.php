<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Mail\Registered;
use App\Models\User;
use App\ValueObjects\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Throwable;

final class Register extends Controller
{
    public function create(): InertiaResponse
    {
        return Inertia::render('auth/Register');
    }

    /**
     * @throws Throwable
     */
    public function store(StoreUserRequest $request): Response|RedirectResponse
    {
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            $user->details()->create([
                'address' => Address::fromArray([
                    'countryCode' => $request->countryCode,
                    'regionCode' => $request->regionCode,
                    'city' => $request->city,
                    'zip' => $request->zip,
                    'lineOne' => $request->lineOne,
                    'lineTwo' => $request->lineTwo,
                ]),
                'tax_number' => $request->taxNumber,
                'phone' => $request->phone,
            ]);

            return $user;
        });

        if (! $user) {
            return new Response('Register has been failed. Please try again later', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        Auth::login($user);

        dispatch(function () use ($user){
            Mail::to($user->email)
                ->sendNow(new Registered($user));
        });

        return redirect(route('dashboard'));
    }
}
