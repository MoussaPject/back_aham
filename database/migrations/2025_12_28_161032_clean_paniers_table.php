<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('paniers', function (Blueprint $table) {
            if (Schema::hasColumn('paniers', 'produit_id')) {
                $table->dropColumn([
                    'produit_id',
                    'quantite',
                    'prix_unitaire',
                    'prix_total',
                    'produit_type'
                ]);
            }
        });
    }

    public function down(): void
    {
        // Pas de rollback : ces champs sont définitivement supprimés
    }
};
