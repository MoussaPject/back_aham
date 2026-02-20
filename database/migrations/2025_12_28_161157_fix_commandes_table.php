<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            if (!Schema::hasColumn('commandes', 'statut')) {
                $table->string('statut')->default('en attente');
            }

            if (!Schema::hasColumn('commandes', 'date_commande')) {
                $table->timestamp('date_commande')->useCurrent();
            }
        });
    }

    public function down(): void
    {
        // rollback non requis
    }
};
