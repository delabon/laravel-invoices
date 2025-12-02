<?php

declare(strict_types=1);

test('guest can see the register form', function () {
    $page = visit(route('register'));

    $page->assertSee('Create an account')
        ->assertSee('Enter your details below to create your account')
        ->assertSee('Full name')
        ->assertSee('Email address')
        ->assertSee('Password')
        ->assertSee('Confirm password')
        ->assertSee('Country')
        ->assertSee('Region')
        ->assertSee('City')
        ->assertSee('Zip')
        ->assertSee('Address line 1')
        ->assertSee('Address line 2')
        ->assertSee('Phone')
        ->assertSee('Tax number')
        ->assertSee('Create account');
});

test('page passes smoke test (no JS errors and console logs)', function () {
    visit(route('register'))->assertNoSmoke();
});

test('guest can register successfully', function () {
    $page = visit(route('register'));

    $page->assertDontSee('Dashboard')
        ->type('name', 'John Doe')
        ->type('email', 'john@doe.test')
        ->type('password', '12pp12pp')
        ->type('password_confirmation', '12pp12pp')
        ->type('phone', '876-123-4321')
        ->type('taxNumber', 'TAX-123-4321')
        ->select('countryCode', 'US')
        ->select('regionCode', 'US-NY')
        ->type('city', 'New York')
        ->type('zip', '10001')
        ->type('lineOne', '123 Main St')
        ->type('lineTwo', 'N97')
        ->click('Create account')
        ->wait(2)
        ->assertDontSee('Create an account')
        ->assertSee('Dashboard')
        ->assertSee('John Doe');
});

test('guest can see error message when there is one', function () {
    $page = visit(route('register'));

    $page->assertDontSee('Dashboard')
        ->type('name', 'John Doe')
        ->type('email', 'john@doe.test')
        ->type('password', '12pp12pp')
        ->type('password_confirmation', 'xxxxxxxx') // invalid password confirmation
        ->type('phone', '876-123-4321')
        ->type('taxNumber', 'TAX-123-4321')
        ->select('countryCode', 'US')
        ->select('regionCode', 'US-NY')
        ->type('city', 'New York')
        ->type('zip', '10001')
        ->type('lineOne', '123 Main St')
        ->type('lineTwo', 'N97')
        ->click('Create account')
        ->wait(1)
        ->assertSee('The password confirmation field confirmation does not match.')
        ->assertDontSee('Dashboard');
});
