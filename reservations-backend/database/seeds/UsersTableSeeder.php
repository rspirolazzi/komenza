<?php

use App\Admin;
use App\Models\Admin\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $password = config('seeding.admin_password');
            if (empty($password)) {
                throw new RuntimeException('Falta definir SEED_ADMIN_PASSWORD');
            }

            $defaultCompany = Company::firstOrCreate(['name' => 'Default']);

            $rows = [
                ['name' => 'Admin', 'last_name' => '', 'email' => 'admin@komenza.example', 'role' => App\Admin::$ROLE_ADMIN, 'password' => Hash::make($password), 'company_id' => $defaultCompany->id],
                ['name' => 'AdminKomenza', 'last_name' => '', 'email' => 'marca@komenza.example', 'role' => App\Admin::$ROLE_BRAND, 'password' => Hash::make($password), 'company_id' => $defaultCompany->id],
                ['name' => 'AdminBarEjemplo', 'last_name' => '', 'email' => 'marca@barejemplo.example', 'role' => App\Admin::$ROLE_BRAND, 'password' => Hash::make($password), 'company_id' => $defaultCompany->id],
            ];
            foreach ($rows as $row) {
                $user = Admin::where('email', $row['email']);
                if ($user == null) {
                    Admin::create($row);
                }
            }
            $stores = \App\Models\Admin\Store::all();
            foreach ($stores as $row) {
                $user = Admin::where('email', $row->email);
                if ($user == null) {
                    $name = $row->name . " " . $row->brand->name;
                    Admin::create([
                        'name' => "Encargado: " . $name,
                        'store_id' => $row->id,
                        'email' => str_replace(' ', '.', strtolower($row->name)) . '@' . str_replace(' ', '.', strtolower($row->brand->name)) . '.com',
                        'role' => App\Admin::$ROLE_STORE,
                        'password' => Hash::make($password),
                    ]);
                }
            }
            $rows = [
                ['name' => 'Administracion', 'email' => 'admin@komenza.example', 'role' => App\Admin::$ROLE_ADMIN, 'password' => Hash::make($password), 'company_id' => $defaultCompany->id],
                ['name' => 'Marca Komenza', 'email' => 'marca@komenza.example', 'role' => App\Admin::$ROLE_BRAND, 'password' => Hash::make($password), 'company_id' => $defaultCompany->id],
                ['name' => 'Marca Bar Ejemplo', 'email' => 'marca@barejemplo.example', 'role' => App\Admin::$ROLE_BRAND, 'password' => Hash::make($password), 'company_id' => $defaultCompany->id],
                ['name' => 'La Lucila', 'email' => 'lalucila@komenza.example', 'role' => App\Admin::$ROLE_STORE, 'password' => Hash::make($password), 'company_id' => $defaultCompany->id],
                ['name' => 'San Isidro', 'email' => 'sanisidro@komenza.example', 'role' => App\Admin::$ROLE_STORE, 'password' => Hash::make($password), 'company_id' => $defaultCompany->id],
                ['name' => 'Vicente Lopez', 'email' => 'vicentelopez@barejemplo.example', 'role' => App\Admin::$ROLE_STORE, 'password' => Hash::make($password), 'company_id' => $defaultCompany->id],
            ];
            foreach ($rows as $row) {
                $user = Admin::where('email', $row['email'])->first();
                if ($user == null) {
                    echo "Creando usuario: " . $row['email'] . "\n";
                    Admin::create($row);
                }else{
                    echo "Usuario ya existe: " . $row['email'] . "\n";
                }
            }
        } catch (\Exception $e) {
            echo "Error al crear el usuario admin: " . $e->getMessage() . "\n";
            die();
        }
    }
}
