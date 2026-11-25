<?php

use Illuminate\Database\Seeder;
use App\Models\Admin\Company;

class CompanySeeder extends Seeder
{
    public function run()
    {
        Company::firstOrCreate(['name' => 'Default']);
    }
}
