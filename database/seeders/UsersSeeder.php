<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions_data = permissions_sync();
        $roles_data = roles_sync();

        // Admin
        $admin = User::create([
            'uuid' => Str::uuid(),
            'username' => "admin",
            'name' => 'Subasta',
            'surname' => 'Administrator',
            'email' => 'admin@subasta.com',
            'configuration' => ['dark' => false, "notificationsPosition" => "bottom"],
            'email_verified_at' => now(),
            'password' => "password",
            'avatar' => 'avatars/default.png',
            'active' => 1
        ]);

        if (isset($admin))
            $admin->assignRole('super-admin');

        $admin = User::create([
            'uuid' => Str::uuid(),
            'username' => "francisco",
            'name' => 'Francisco',
            'surname' => '',
            'email' => 'frannglezlara@gmail.com',
            'configuration' => ['dark' => false, "notificationsPosition" => "bottom"],
            'email_verified_at' => now(),
            'password' => "guadasu1",
            'avatar' => 'avatars/default.png',
            'active' => 1
        ]);

        if (isset($admin))
            $admin->assignRole('super-admin');

        $admin = User::create([
            'uuid' => Str::uuid(),
            'username' => "dani",
            'name' => 'Daniel',
            'surname' => '',
            'email' => 'danielgmen@hotmail.com',
            'configuration' => ['dark' => false, "notificationsPosition" => "bottom"],
            'email_verified_at' => now(),
            'password' => "guadasu1",
            'avatar' => 'avatars/default.png',
            'active' => 1
        ]);

        if (isset($admin))
            $admin->assignRole('super-admin');

        $user = User::create([
            'uuid' => Str::uuid(),
            'username' => "usuario",
            'name' => 'Subasta',
            'surname' => 'Usuario',
            'email' => 'usuario@subasta.com',
            'configuration' => ['dark' => false, "notificationsPosition" => "top-right"],
            'email_verified_at' => now(),
            'password' => "password",
            'avatar' => 'avatars/default.png',
            'active' => 1
        ]);

        if (isset($user))
            $user->assignRole('client');


    }
}
