<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Users\StoreUserAction;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Throwable;

final class RegisterController extends Controller
{
    public function create(): InertiaResponse
    {
        return Inertia::render('auth/Register');
    }

    /**
     * @throws Throwable
     */
    public function store(StoreUserRequest $request, StoreUserAction $action): Response|RedirectResponse
    {
        try {
            $action->execute($request->toDto());

            return redirect(route('dashboard'));
        } catch (Throwable $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
