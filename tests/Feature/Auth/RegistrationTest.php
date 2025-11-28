<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\UserDetail;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\assertDatabaseCount;

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertStatus(200);
    $response->assertInertia(static fn (Assert $page) => $page
        ->component('auth/Register')
    );
});

test('new users can register', function () {
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
    $response = $this->post(route('register.store'), $userData);

    $this->assertAuthenticated();
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('dashboard', absolute: false));

    /** @var User $user */
    $user = auth()->user();
    /** @var UserDetail $userDetails */
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

it('fails when trying to register a new user with an existent email', function () {
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

    // Should succeed
    $this->post(route('register.store'), $userData);

    assertDatabaseCount('users', 1);

    auth()->logout();

    // Should fail
    $response2 = $this->post(route('register.store'), $userData);

    $response2->assertSessionHasErrors([
        'email' => 'The email has already been taken.'
    ]);

    assertDatabaseCount('users', 1);
});

it('fails when trying to register when authenticated', function () {
    $this->actingAs(UserFactory::new()->create());

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

    $response = $this->post(route('register.store'), $userData);

    $response->assertRedirect(route('login'));

    assertDatabaseCount('users', 1);
});

