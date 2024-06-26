<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * Roles
         */
        $admin = \Spatie\Permission\Models\Role::findOrCreate(config('permission.super_admin_role_name'), 'api');

        /**
         * Permissions
         */
        $models = \App\Helpers\get_models();
        $base_permissions = config('permission.base_permissions');
        foreach ($models as $model){
            $model = strtolower($model['name']);
            foreach ($base_permissions as $p){
                $permission = Spatie\Permission\Models\Permission::findOrCreate("$model:$p", 'api');
                $admin->givePermissionTo($permission);
            }
        }

        /**
         * Users
         */
        User::create([
                'uuid'=> Str::uuid(),
                'username' => "admin",
                'name' => 'Subasta',
                'surname' => 'Administrator',
                'email' => 'admin@subasta.com',
                'email_verified_at' => \Carbon\Carbon::now(),
                'password' => "password",
                'avatar'=>'avatars/default.png'
        ]);

            $user = User::where('username', '=', 'admin')->first();
            $user->assignRole('super-admin');



    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       //
        \Illuminate\Support\Facades\DB::table('users')->truncate();
        \Illuminate\Support\Facades\DB::table('roles')->truncate();
        \Illuminate\Support\Facades\DB::table('permissions')->truncate();
    }
};
