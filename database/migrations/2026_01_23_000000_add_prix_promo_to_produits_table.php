<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            if (!Schema::hasColumn('produits', 'prix_promo')) {
                $table->decimal('prix_promo', 8, 2)->nullable()->after('prix');
            }

            if (!Schema::hasColumn('produits', 'en_promotion')) {
                $table->boolean('en_promotion')->default(false)->after('prix_promo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            if (Schema::hasColumn('produits', 'prix_promo')) {
                $table->dropColumn('prix_promo');
            }

            if (Schema::hasColumn('produits', 'en_promotion')) {
                $table->dropColumn('en_promotion');
            }
        });
    }
};
