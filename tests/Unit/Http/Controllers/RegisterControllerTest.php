<?php

declare(strict_types=1);

use App\Actions\Users\StoreUserAction;
use App\Http\Controllers\RegisterController;
use App\Http\Requests\StoreUserRequest;
use Database\Factories\UserFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Inertia\Response as InertiaResponse;

use function Pest\Laravel\assertDatabaseCount;

it('renders the register page component successfully', function () {
    $controller = new RegisterController();

    $response = $controller->create();

    expect($response)->toBeInstanceOf(InertiaResponse::class);
});

it('stores a new user successfully', function () {
    $controller = new RegisterController();
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'countryCode' => 'US',
        'regionCode' => 'US-CA',
        'city' => 'Los Angeles',
        'zip' => '9003',
        'lineOne' => 'Main St 123',
        'lineTwo' => 'N453',
        'phone' => '123-4567-567',
        'taxNumber' => 'TAX-123-456',
    ];
    $request = new StoreUserRequest();
    $request->replace($userData);
    $action = new StoreUserAction();

    $response = $controller->store(
        $request,
        $action
    );

    assertDatabaseCount('users', 1);
    $this->assertAuthenticated();

    expect($response)->toBeInstanceOf(RedirectResponse::class)
        ->and($response->isRedirect(route('dashboard')))->toBeTrue();

    $user = auth()->user();
    $userDetails = $user->details;

    expect($user->id)->toBe(1)
        ->and($user->email)->toBe($userData['email'])
        ->and($user->name)->toBe($userData['name'])
        ->and(Hash::check($userData['password'], $user->password))->toBeTrue()
        ->and($userDetails->address->countryCode)->toBe($userData['countryCode'])
        ->and($userDetails->address->regionCode)->toBe($userData['regionCode'])
        ->and($userDetails->address->city)->toBe($userData['city'])
        ->and($userDetails->address->zip)->toBe($userData['zip'])
        ->and($userDetails->address->lineOne)->toBe($userData['lineOne'])
        ->and($userDetails->address->lineTwo)->toBe($userData['lineTwo'])
        ->and($userDetails->phone)->toBe($userData['phone'])
        ->and($userDetails->tax_number)->toBe($userData['taxNumber']);
});

it('fails when trying to register a user with an existent email', function () {
    UserFactory::new()->create([
        'email' => 'exitent@cc.test',
    ]);
    $controller = new RegisterController();
    $userData = [
        'name' => 'Test User',
        'email' => 'exitent@cc.test',
        'password' => 'password',
        'password_confirmation' => 'password',
        'countryCode' => 'US',
        'regionCode' => 'US-CA',
        'city' => 'Los Angeles',
        'zip' => '9003',
        'lineOne' => 'Main St 123',
        'lineTwo' => 'N453',
        'phone' => '123-4567-567',
        'taxNumber' => 'TAX-123-456',
    ];
    $request = new StoreUserRequest();
    $request->replace($userData);
    $action = new StoreUserAction();

    $response = $controller->store(
        $request,
        $action
    );

    assertDatabaseCount('users', 1);
    $this->assertGuest();

    expect($response)->toBeInstanceOf(Response::class)
        ->and($response->getStatusCode())->toBe(Response::HTTP_INTERNAL_SERVER_ERROR)
        ->and($response->getContent())->toBe('Registration failed. Please try again later.');
});
