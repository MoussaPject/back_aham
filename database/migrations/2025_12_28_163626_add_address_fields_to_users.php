<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telephone')->nullable();
            $table->string('pays')->default('Sénégal');
            $table->string('ville')->nullable();
            $table->string('quartier')->nullable();
            $table->text('adresse')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'telephone',
                'pays',
                'ville',
                'quartier',
                'adresse'
            ]);
        });
    }
};
