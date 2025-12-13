<?php

declare(strict_types=1);

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
        Schema::create('trades', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('buy_order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignUuid('sell_order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->decimal('price', 20, 8);
            $table->decimal('amount', 30, 18);
            $table->decimal('usd_volume', 24, 8);
            $table->decimal('fee_usd', 24, 8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
