<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(CompanySeeder::class);
        $this->call(BrandAndStoreSeeder::class);
        $this->call(BrandBackgroundSeeder::class);
		$this->call(UsersTableSeeder::class);
        $this->call(ReservationTableSeed::class);
    }
}
