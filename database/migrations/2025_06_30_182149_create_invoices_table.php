<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Client::class)->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('uid', length: 50);
            $table->string('title');
            $table->string('currency', 10);
            $table->integer('tax_amount');
            $table->integer('total_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
