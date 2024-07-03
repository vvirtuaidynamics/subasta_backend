<?php

namespace App\Console\Commands;

use App\Models\Module;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class Security extends Command
{
    /**
     * Command for check and sync user modules roles and permissions.
     *
     * @var string
     */
    protected $signature = 'security:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for check and sync user modules roles and permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        //!TODO: IMPLEMENTAR LOGICA PARA SINCRONIZAR MODELOS Y PERMISOS DEL FICHERO DE CONFIGURACION CON LA BASE DE DATOS.
        // Se llamaran a las funciones get_modules para obtener el listado de modelos y sus permisos, sincronizadon estos con la base de datos.
        try {
            $sync_modules = $this->confirm(' Sync modules too? (Si/No)', true);
            if (!$sync_modules) {
                $tablename = "modules";
                $start_modules = Module::all();
                $modules = get_modules();
                DB::table($tablename)->truncate();
                DB::table($tablename)->insert([
                    ...$modules
                ]);
                $end_modules = Module::all();
                $diff_modules = $end_modules->count() - $start_modules->count();
                if ($diff_modules > 0) {
                    $this->info("Added $diff_modules modules.");
                }
            }
            $this->info("Modules sync successful.");

            // Check update and create permissions
            $permissions_data = permissions_sync();
            $this->info("Permissions update successful.");
            if (isset($permissions_data['diff']) && intval($permissions_data['diff']) > 0) {
                $added_perm = intval($permissions_data['diff']);
                $this->info("Added $added_perm permissions.");
            }
            $total_perm = intval($permissions_data['total']);
            $this->info("$total_perm permissions sync successful.");

            // Check update and create roles
            $roles_data = roles_sync();
            if (isset($roles_data['diff']) && intval($roles_data['diff']) > 0) {
                $added_role = intval($roles_data['diff']);
                $this->info("Added $added_role permissions.");
            }
            $total_perm = intval($roles_data['total']);
            $this->info("$total_perm role sync successful.");
            $this->info("Operation completed successfully.");
            $teminate = $this->confirm(' Repeat operation (Si/No)', true);
            if ($teminate) $this->handle();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

    }
}
