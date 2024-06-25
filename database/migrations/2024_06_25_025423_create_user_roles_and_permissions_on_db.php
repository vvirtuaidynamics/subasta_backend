<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
        $admin = \Spatie\Permission\Models\Role::create(['name' => 'super-admin']);

        /**
         * Permissions
         */
        $models = \App\Helpers\get_models();
        $base_permissions = config('permission.base_permissions');
        foreach ($models as $model){
            $model = strtolower($model['name']);
            foreach ($base_permissions as $p){
                $permission = Spatie\Permission\Models\Permission::create(['name'=>"$model:$p", 'guard_name'=>'api']);
                $admin->givePermissionTo($permission);
            }
        }

        /**
         * Users
         */
        $success = \Illuminate\Support\Facades\DB::table('users')
            ->insert([
                'username' => "admin",
                'name' => 'Subasta',
                'surname' => 'Administrator',
                'email' => 'admin@subasta.com',
                'email_verified_at' => \Carbon\Carbon::now(),
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'avatar'=>'avatar/default.png'
            ]);

        if($success){
            $user = \App\Models\User::all()->where('username', '=', 'admin')->first();
            $user->assignRole('super-admin');


        }
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
