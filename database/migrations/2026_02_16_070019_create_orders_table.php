<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->string('firstname');          // Nouveau champ
            $table->string('lastname');           // Nouveau champ
            $table->string('email');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->string('payment_method');
            $table->string('status')->default('pending');
            $table->json('payment_details')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('email');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};