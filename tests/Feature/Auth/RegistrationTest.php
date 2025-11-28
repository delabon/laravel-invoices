<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\UserDetail;
use App\Rules\ValidCity;
use App\Rules\ValidCountryCode;
use App\Rules\ValidName;
use App\Rules\ValidPassword;
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
    $this->assertGuest();
});

it('fails when trying to register when authenticated', function () {
    $mainUser = UserFactory::new()->create();
    $this->actingAs($mainUser);

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
    expect(auth()->user()->id)->toBe($mainUser->id);
});

it('fails with invalid registration data', function (array $invalidData, string $errorField, string $expectedErrorMessage) {
    $baseValidData = [
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

    $testData = array_merge($baseValidData, $invalidData);

    $response = $this->post(route('register.store'), $testData);

    $response->assertSessionHasErrors([
        $errorField => $expectedErrorMessage
    ]);
    $this->assertGuest();
    assertDatabaseCount('users', 0);
})->with([
    'missing name' => [
        [
            'name' => '',
        ],
        'name',
        'The name field is required.',
    ],
    'name too long' => [
        [
            'name' => str_repeat('a', ValidName::MAX_LENGTH + 1)
        ],
        'name',
        'The name field must not be greater than '.ValidName::MAX_LENGTH.' characters.',
    ],

    'missing email' => [
        [
            'email' => '',
        ],
        'email',
        'The email field is required.',
    ],
    'invalid email format' => [
        [
            'email' => 'notanemail',
        ],
        'email',
        'The email field must be a valid email address.',
    ],
    'email without domain' => [
        [
            'email' => 'test@',
        ],
        'email',
        'The email field must be a valid email address.',
    ],
    'email without @' => [
        [
            'email' => 'testexample.com',
        ],
        'email',
        'The email field must be a valid email address.',
    ],

    'missing password' => [
        [
            'password' => '',
        ],
        'password',
        'The password field is required.',
    ],
    'password too short' => [
        [
            'password' => str_repeat('b', ValidPassword::MIN_LENGTH - 1),
            'password_confirmation' => 'pass',
        ],
        'password',
        'The password field must be at least '.ValidPassword::MIN_LENGTH.' characters.',
    ],
    'password too long' => [
        [
            'password' => str_repeat('a', ValidPassword::MAX_LENGTH + 1),
            'password_confirmation' => str_repeat('a', ValidPassword::MAX_LENGTH + 1),
        ],
        'password',
        'The password field must not be greater than 255 characters.',
    ],

    'missing password confirmation' => [
        [
            'password_confirmation' => '',
        ],
        'password_confirmation',
        'The password confirmation field is required.',
    ],
    'password confirmation mismatch' => [
        [
            'password_confirmation' => 'different',
        ],
        'password_confirmation',
        'The password confirmation field confirmation does not match.',
    ],

    'missing countryCode' => [
        [
            'countryCode' => ''
        ],
        'countryCode',
        'The country code field is required.'
    ],
    'invalid countryCode format' => [
        [
            'countryCode' => str_repeat('A', ValidCountryCode::CODE_LENGTH + 1),
        ],
        'countryCode',
        'The country code field must be '.ValidCountryCode::CODE_LENGTH.' characters.',
    ],
    'countryCode too short' => [
        [
            'countryCode' => 'U',
        ],
        'countryCode',
        'The country code field must be 2 characters.',
    ],
    'countryCode with numbers' => [
        [
            'countryCode' => 'U1',
        ],
        'countryCode',
        'The country code field format is invalid.',
    ],
    'countryCode lowercase' => [
        [
            'countryCode' => 'us',
        ],
        'countryCode',
        'The country code field format is invalid.',
    ],

    'missing regionCode' => [
        [
            'regionCode' => '',
        ],
        'regionCode',
        'The region code field is required.',
    ],
    'invalid regionCode format' => [
        [
            'regionCode' => 'USCA',
        ],
        'regionCode',
        'The region code field format is invalid.',
    ],
    'regionCode without hyphen' => [
        [
            'regionCode' => 'US CA',
        ],
        'regionCode',
        'The region code field format is invalid.',
    ],
    'regionCode lowercase' => [
        [
            'regionCode' => 'us-ca',
        ],
        'regionCode',
        'The region code field format is invalid.',
    ],

    'missing city' => [
        [
            'city' => '',
        ],
        'city',
        'The city field is required.',
    ],
    'city too long' => [
        [
            'city' => str_repeat('a', ValidCity::MAX_LENGTH + 1),
        ],
        'city',
        'The city field must not be greater than 50 characters.',
    ],

    'missing zip' => [
        [
            'zip' => '',
        ],
        'zip',
        'The zip field is required.',
    ],
    'zip with special chars' => [
        [
            'zip' => '9003@',
        ], 'zip',
        'The zip field format is invalid.',
    ],
    'zip too long' => [['zip' => str_repeat('1', 21)], 'zip', 'The zip field must not be greater than 20 characters.'],
    'zip starts with hyphen' => [['zip' => '-9003'], 'zip', 'The zip field format is invalid.'],
    'zip ends with hyphen' => [['zip' => '9003-'], 'zip', 'The zip field format is invalid.'],
    'zip single character' => [['zip' => '9'], 'zip', 'The zip field format is invalid.'],

    'missing lineOne' => [['lineOne' => ''], 'lineOne', 'The line one field is required.'],
    'lineOne too long' => [['lineOne' => str_repeat('a', 256)], 'lineOne', 'The line one field must not be greater than 255 characters.'],

    'lineTwo too long' => [['lineTwo' => str_repeat('a', 256)], 'lineTwo', 'The line two field must not be greater than 255 characters.'],

    'missing phone' => [['phone' => ''], 'phone', 'The phone field is required.'],
    'phone too long' => [['phone' => str_repeat('1', 21)], 'phone', 'The phone field must not be greater than 20 characters.'],

    'missing taxNumber' => [['taxNumber' => ''], 'taxNumber', 'The tax number field is required.'],
    'taxNumber too long' => [['taxNumber' => str_repeat('a', 51)], 'taxNumber', 'The tax number field must not be greater than 50 characters.'],
]);

