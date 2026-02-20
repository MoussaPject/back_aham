<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('panier_items', function (Blueprint $table) {
            if (!Schema::hasColumn('panier_items', 'panier_id')) {
                $table->foreignId('panier_id')
                    ->constrained()
                    ->cascadeOnDelete();
            }

            if (Schema::hasColumn('panier_items', 'produit_type')) {
                $table->dropColumn('produit_type');
            }
        });
    }

    public function down(): void
    {
        // rollback non nécessaire
    }
};
