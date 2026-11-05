<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bill_of_ladings', function (Blueprint $table) {
            $table->id();
            $table->string('bl_number')->unique();
            $table->string('shipper');
            $table->string('consignee');
            $table->string('notify_party')->nullable();
            $table->string('carrier');
            $table->string('vessel_name');
            $table->string('voyage_no');
            $table->string('port_of_loading');
            $table->string('port_of_discharge');
            $table->text('cargo_description');
            $table->string('quantity');
            $table->string('weight');
            $table->json('container_numbers')->nullable();
            $table->string('current_owner');
            $table->string('status')->default('issued');
            $table->string('data_hash', 64);
            $table->string('blockchain_tx_hash')->nullable();
            $table->foreignId('block_id')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();
        });

        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('index')->unique();
            $table->timestamp('timestamp');
            $table->json('data');
            $table->string('previous_hash', 64);
            $table->string('hash', 64)->unique();
            $table->unsignedInteger('nonce')->default(0);
            $table->timestamps();
        });

        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_of_lading_id')->constrained()->cascadeOnDelete();
            $table->string('from_owner');
            $table->string('to_owner');
            $table->string('reason')->nullable();
            $table->string('blockchain_tx_hash')->nullable();
            $table->timestamp('transferred_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfers');
        Schema::dropIfExists('blocks');
        Schema::dropIfExists('bill_of_ladings');
    }
};
