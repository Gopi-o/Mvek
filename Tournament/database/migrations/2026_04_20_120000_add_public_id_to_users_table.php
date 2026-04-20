<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('public_id', 12)->nullable()->unique()->after('role');
        });

        DB::table('users')->orderBy('id')->chunkById(100, function ($users) {
            foreach ($users as $user) {
                if (!empty($user->public_id)) {
                    continue;
                }

                do {
                    $publicId = strtoupper(Str::random(10));
                } while (DB::table('users')->where('public_id', $publicId)->exists());

                DB::table('users')->where('id', $user->id)->update(['public_id' => $publicId]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['public_id']);
            $table->dropColumn('public_id');
        });
    }
};

