<?php

declare(strict_types=1);

use App\Models\UserDetail;
use App\Rules\ValidName;
use App\ValueObjects\Address;
use App\ValueObjects\UserDetails;
use Database\Factories\UserDetailFactory;
use Database\Factories\UserFactory;
use Illuminate\Validation\ValidationException;

it('creates an instance of UserDetails successfully', function () {
    $address = new Address(
        countryCode: 'TN',
        regionCode: 'TN-23',
        city: 'Jarzouna',
        zip: 'TEST-7021',
        lineOne: 'Place 123',
        lineTwo: 'N45',
    );
    $userDetails = new UserDetails(
        name: 'John Doe',
        email: 'john@doe.test',
        address: $address,
        taxNumber: 'TAX123',
        phone: '98322131'
    );

    expect($userDetails)->toBeInstanceOf(UserDetails::class)
        ->and($userDetails->name)->toBe('John Doe')
        ->and($userDetails->email)->toBe('john@doe.test')
        ->and($userDetails->address)->toBeInstanceOf(Address::class)
        ->and($userDetails->address)->toBe($address)
        ->and($userDetails->taxNumber)->toBe('TAX123')
        ->and($userDetails->phone)->toBe('98322131');
});

it('creates an instance of UserDetails from an array of data', function () {
    $address = new Address(
        countryCode: 'US',
        regionCode: 'US-NY',
        city: 'New York City',
        zip: '10003',
        lineOne: 'Main St 123',
        lineTwo: 'N123',
    );
    $userDetails = UserDetails::fromArray([
        'name' => 'Mike Doe',
        'email' => 'mike@doe.test',
        'address' => $address,
        'taxNumber' => 'US-TAX-9912',
        'phone' => '0023-234-213',
    ]);

    expect($userDetails)->toBeInstanceOf(UserDetails::class)
        ->and($userDetails->name)->toBe('Mike Doe')
        ->and($userDetails->email)->toBe('mike@doe.test')
        ->and($userDetails->address)->toBeInstanceOf(Address::class)
        ->and($userDetails->address)->toBe($address)
        ->and($userDetails->taxNumber)->toBe('US-TAX-9912')
        ->and($userDetails->phone)->toBe('0023-234-213');
});

it('throws an InvalidArgumentException when create an instance of UserDetails from an array with invalid address', function () {
    expect(static fn () => UserDetails::fromArray([
        'name' => 'Mike Doe',
        'address' => '',
        'email' => 'mike@doe.cc',
        'phone' => '667-123-432',
        'taxNumber' => 'TC-1234-4214',
    ]))->toThrow(InvalidArgumentException::class, 'The address parameter must be an instance of Address value object.');
});

test('to array', function () {
    $address = new Address(
        countryCode: 'US',
        regionCode: 'US-NY',
        city: 'New York',
        zip: '10001',
        lineOne: 'Main St 123',
        lineTwo: null,
    );
    $userDetails = new UserDetails(
        name: 'Mike Doe',
        email: 'mike@doe.test',
        address: $address,
        taxNumber: 'US-TAX-876',
        phone: '0123-234-213'
    );

    expect($userDetails->toArray())->toBe([
        'name' => 'Mike Doe',
        'email' => 'mike@doe.test',
        'address' => $address,
        'taxNumber' => 'US-TAX-876',
        'phone' => '0123-234-213',
    ]);
});

it('creates an instance of UserDetails from a User model', function () {
    $user = UserFactory::new()->create();
    UserDetailFactory::new()->create([
        'user_id' => $user->id,
    ]);
    $user->refresh();

    $userDetails = UserDetails::fromUser($user);

    expect($userDetails->toArray())->toBe([
        'name' => $user->name,
        'email' => $user->email,
        'address' => $user->details->address,
        'taxNumber' => $user->details->tax_number,
        'phone' => $user->details->phone,
    ]);
});

dataset('invalid_name_data', [
    [
        '', // empty
        'The name field is required.',
    ],
    [
        str_repeat('a', ValidName::MAX_LENGTH + 1), // larger than max
        'The name field must not be greater than 255 characters.',
    ],
]);

it('fails with invalid name data', function (string $invalidName, string $expectedMessage) {
    $address = new Address(
        countryCode: 'US',
        regionCode: 'US-NY',
        city: 'New York',
        zip: '10001',
        lineOne: 'Main St 123',
        lineTwo: null,
    );

    expect(fn () => new UserDetails(
        name: $invalidName,
        email: 'paul@doe.cc',
        address: $address,
        taxNumber: '923784',
        phone: '123-4123-512',
    ))->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_name_data');

dataset('invalid_email_data', [
    [
        '', // empty
        'The email field is required.',
    ],
    [
        'super text', // not valid email
        'The email field must be a valid email address.',
    ],
    [
        'super@text #$*%#@%', // not valid email
        'The email field must be a valid email address.',
    ],
]);

it('fails with invalid email data', function (string $invalidEmail, string $expectedMessage) {
    $address = new Address(
        countryCode: 'US',
        regionCode: 'US-NY',
        city: 'New York',
        zip: '10001',
        lineOne: 'Main St 123',
        lineTwo: null,
    );

    expect(fn () => new UserDetails(
        name: 'Paul Doe',
        email: $invalidEmail,
        address: $address,
        taxNumber: '923784',
        phone: '123-4123-512',
    ))->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_email_data');

dataset('invalid_tax_number_data', [
    [
        '', // empty
        'The tax number field is required.',
    ],
    [
        str_repeat('a', UserDetail::MAX_TAX_NUMBER_LENGTH + 1), // more than max
        'The tax number field must not be greater than 50 characters.',
    ],
]);

it('fails with invalid tax number data', function (string $invalidTaxNumber, string $expectedMessage) {
    $address = new Address(
        countryCode: 'US',
        regionCode: 'US-NY',
        city: 'New York',
        zip: '10001',
        lineOne: 'Main St 123',
        lineTwo: null,
    );

    expect(fn () => new UserDetails(
        name: 'Paul Doe',
        email: 'paul@doe.com',
        address: $address,
        taxNumber: $invalidTaxNumber,
        phone: '123-4123-512',
    ))->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_tax_number_data');

dataset('invalid_phone_data', [
    [
        '', // empty
        'The phone field is required.',
    ],
    [
        str_repeat('a', UserDetail::MAX_PHONE_LENGTH + 1), // more than max
        'The phone field must not be greater than 20 characters.',
    ],
]);

it('fails with invalid phone data', function (string $invalidPhone, string $expectedMessage) {
    $address = new Address(
        countryCode: 'US',
        regionCode: 'US-NY',
        city: 'New York',
        zip: '10001',
        lineOne: 'Main St 123',
        lineTwo: null,
    );

    expect(fn () => new UserDetails(
        name: 'Paul Doe',
        email: 'paul@doe.com',
        address: $address,
        taxNumber: '123843',
        phone: $invalidPhone,
    ))->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_phone_data');
