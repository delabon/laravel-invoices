<?php

declare(strict_types=1);

use App\Actions\Users\StoreUserAction;
use App\DTOs\NewUserDTO;
use App\Mail\Registered;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Queue\CallQueuedClosure;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

use function Pest\Laravel\assertDatabaseCount;

it('creates a new user successfully', function () {
    Mail::fake();
    Queue::fake();

    $action = new StoreUserAction();

    $user = $action->execute(new NewUserDTO(
        name: 'Mike Tall',
        email: 'mike@test.com',
        password: '12345678',
        phone: '123-123-123',
        taxNumber: 'TVA-123-4321-445',
        countryCode: 'US',
        regionCode: 'US-NY',
        city: 'New York',
        zip: '10001',
        lineOne: '123 Main St',
        lineTwo: 'N54',
    ));

    $userDetails = $user->details;

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->id)->toBe(1)
        ->and($user->name)->toBe('Mike Tall')
        ->and($user->email)->toBe('mike@test.com')
        ->and(Hash::check('12345678', $user->password))->toBeTrue()
        ->and($userDetails->address->countryCode)->toBe('US')
        ->and($userDetails->address->regionCode)->toBe('US-NY')
        ->and($userDetails->address->city)->toBe('New York')
        ->and($userDetails->address->zip)->toBe('10001')
        ->and($userDetails->address->lineOne)->toBe('123 Main St')
        ->and($userDetails->address->lineTwo)->toBe('N54')
        ->and($userDetails->phone)->toBe('123-123-123')
        ->and($userDetails->tax_number)->toBe('TVA-123-4321-445');
    assertDatabaseCount('users', 1);
    $this->assertAuthenticatedAs($user);

    Queue::assertPushed(CallQueuedClosure::class, function ($job) {
        $job->closure->getClosure()();  // Very important to execute

        Mail::assertSent(Registered::class, function ($mail) {
            return $mail->hasTo('mike@test.com');
        });

        return true;
    });
});

it('creates a new user with a null address line two', function () {
    $action = new StoreUserAction();

    $user = $action->execute(new NewUserDTO(
        name: 'Mike Tall',
        email: 'mike@test.com',
        password: '12345678',
        phone: '123-123-123',
        taxNumber: 'TVA-123-4321-445',
        countryCode: 'US',
        regionCode: 'US-NY',
        city: 'New York',
        zip: '10001',
        lineOne: '123 Main St',
        lineTwo: null,
    ));

    expect($user->details->lineTwo)->toBeNull();
    assertDatabaseCount('users', 1);
});

it('fails when trying to create a new user with an existent email', function () {
    UserFactory::new()->create([
        'email' => 'mike@test.com',
    ]);
    $action = new StoreUserAction();

    expect(static fn () => $action->execute(new NewUserDTO(
        name: 'Mike Tall',
        email: 'mike@test.com',
        password: '12345678',
        phone: '123-123-123',
        taxNumber: 'TVA-123-4321-445',
        countryCode: 'US',
        regionCode: 'US-NY',
        city: 'New York',
        zip: '10001',
        lineOne: '123 Main St',
        lineTwo: null,
    )))->toThrow(RuntimeException::class, 'Registration failed. Please try again later.');
});
