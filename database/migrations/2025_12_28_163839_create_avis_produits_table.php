<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('avis_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('note'); // 1 à 5
            $table->text('commentaire')->nullable();
            $table->boolean('valide')->default(false);
            $table->timestamps();

            $table->unique(['produit_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avis_produits');
    }
};
