
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->nullable()->unique()->after('name');
        });

        DB::table('users')->orderBy('id')->each(function ($user) {
            $base = \Illuminate\Support\Str::slug($user->name, '');
            $base = $base ?: 'user';

            $username = substr($base, 0, 40);
            $suffix = 1;

            while (DB::table('users')->where('username', $username)->exists()) {
                $username = substr($base, 0, 40) . $suffix++;
            }

            DB::table('users')->where('id', $user->id)->update([
                'username' => $username,
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
