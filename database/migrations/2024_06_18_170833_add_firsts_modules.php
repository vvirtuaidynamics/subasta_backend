<?php

use App\Models\Application;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $default_permissions = [
            [
                'code' => 'view'
            ],
            [
                'code' => 'create'
            ],
            [
                'code' => 'update'
            ],
            [
                'code' => 'delete',
            ],
            [
                'code' => 'history',
            ],
        ];

        $user = User::create([
            'user' => 'admin',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'email' => 'admin@gmail.com',
            'password' => 'password',
            'sa' => true,
            'active' => true
        ]);

        $user = User::create([
            'user' => 'test',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'sa' => false,
            'active' => true
        ]);

        $role = Role::create([
            'name' => 'Transportista'
        ]);

        $user->roles()->save($role);

        $app = Application::create([
            'label' => 'administration',
            'ico' => 'mdi-account-cog',
            'order' => 1
        ]);

        $module = Module::create([
            'label' => 'users',
            'ico' => 'mdi-account-multiple',
            'model_name' => 'User',
            'model_namespace' => 'App\\Models',
            'order' => 1,
            'application_id' => $app->id
        ]);

        foreach ($default_permissions as $p) {
            if ($p['code'] != 'delete') {
                Permission::create([
                    'code' => $p['code'] . '_' . strtolower($module->model_name),
                    'name' => $p['code'],
                    'module_id' => $module->id
                ]);
            }
        }

        $module = Module::create([
            'label' => 'groups',
            'ico' => 'mdi-account-group',
            'model_name' => 'Role',
            'model_namespace' => 'App\\Models',
            'orden' => 2,
            'application_id' => $app->id
        ]);

        foreach ($default_permissions as $p) {
            Permission::create([
                'code' => $p['code'] . '_' . strtolower($module->model_name),
                'name' => $p['code'],
                'module_id' => $module->id
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::all()->delete();
        Role::all()->delete();
        Permission::all()->delete();
        Module::all()->delete();
        Application::all()->delete();
    }
};