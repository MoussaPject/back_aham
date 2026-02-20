<?php

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
        Schema::table('paniers', function (Blueprint $table) {
            $table->string('produit_type')->after('user_id');
            $table->unsignedBigInteger('produit_id')->after('produit_type');
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire', 8, 2)->default(0);
            $table->decimal('prix_total', 8, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paniers', function (Blueprint $table) {
            $table->dropColumn(['produit_type', 'produit_id', 'quantite', 'prix_unitaire', 'prix_total']);
        });
    }
};
