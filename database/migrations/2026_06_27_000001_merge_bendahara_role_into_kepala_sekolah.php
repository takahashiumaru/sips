<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'bendahara')
            ->update(['role' => 'kepala_sekolah']);

        if (!Schema::hasTable('roles')) {
            return;
        }

        $kepalaSekolahRoleId = DB::table('roles')->where('name', 'kepala_sekolah')->value('id');
        $bendaharaRoleId = DB::table('roles')->where('name', 'bendahara')->value('id');

        if (!$bendaharaRoleId) {
            return;
        }

        if ($kepalaSekolahRoleId) {
            if (Schema::hasTable('model_has_roles')) {
                $modelRoles = DB::table('model_has_roles')
                    ->where('role_id', $bendaharaRoleId)
                    ->get();

                foreach ($modelRoles as $modelRole) {
                    DB::table('model_has_roles')->insertOrIgnore([
                        'role_id' => $kepalaSekolahRoleId,
                        'model_type' => $modelRole->model_type,
                        'model_id' => $modelRole->model_id,
                    ]);
                }

                DB::table('model_has_roles')
                    ->where('role_id', $bendaharaRoleId)
                    ->delete();
            }

            DB::table('roles')->where('id', $bendaharaRoleId)->delete();
            return;
        }

        DB::table('roles')
            ->where('id', $bendaharaRoleId)
            ->update(['name' => 'kepala_sekolah']);
    }

    public function down(): void
    {
        if (Schema::hasTable('roles')) {
            DB::table('roles')->updateOrInsert(
                ['name' => 'bendahara', 'guard_name' => 'web'],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
};
