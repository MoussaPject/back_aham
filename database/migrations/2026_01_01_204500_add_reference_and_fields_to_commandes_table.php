<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            if (!Schema::hasColumn('commandes', 'reference')) {
                $table->string('reference')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('commandes', 'mode_livraison')) {
                $table->string('mode_livraison')->nullable()->after('date_commande');
            }

            if (!Schema::hasColumn('commandes', 'note_client')) {
                $table->text('note_client')->nullable()->after('mode_livraison');
            }
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            if (Schema::hasColumn('commandes', 'reference')) {
                $table->dropColumn('reference');
            }
            if (Schema::hasColumn('commandes', 'mode_livraison')) {
                $table->dropColumn('mode_livraison');
            }
            if (Schema::hasColumn('commandes', 'note_client')) {
                $table->dropColumn('note_client');
            }
        });
    }
};
