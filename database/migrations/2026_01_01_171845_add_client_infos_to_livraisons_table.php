<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('livraisons', function (Blueprint $table) {

            // Infos client
            $table->string('nom_client')->after('commande_id');
            $table->string('telephone', 30)->after('nom_client');
            $table->string('adresse')->after('telephone');
            $table->string('ville')->after('adresse');
            $table->text('instructions')->nullable()->after('ville');

        });
    }

    public function down(): void
    {
        Schema::table('livraisons', function (Blueprint $table) {
            $table->dropColumn([
                'nom_client',
                'telephone',
                'adresse',
                'ville',
                'instructions',
            ]);
        });
    }
};
