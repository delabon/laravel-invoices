<?php

declare(strict_types=1);

use App\Models\Invoice;
use Database\Factories\InvoiceFactory;
use Database\Factories\InvoiceItemFactory;

test('to array', function () {
    $invoice = InvoiceItemFactory::new()->create();

    expect($invoice->refresh()->toArray())->toHaveKeys([
        'id',
        'invoice_id',
        'name',
        'quantity',
        'unit_price',
        'subtotal',
        'tax',
        'total',
        'created_at',
        'updated_at',
    ]);
});

it('belongs to an invoice', function () {
    $invoice = InvoiceFactory::new()->create();

    $item = InvoiceItemFactory::new()->create([
        'invoice_id' => $invoice->id,
    ]);

    $item->load('invoice');

    expect($item->invoice)->toBeInstanceOf(Invoice::class)
        ->and($item->invoice->id)->toBe($invoice->id);
});
