<?php

use App\Models\Admin\Brand;
use App\Models\Admin\Store;
use Illuminate\Database\Seeder;

class BrandAndStoreSeeder extends Seeder
{
    public static $BRANDS = [
        ['name' => 'Komenza', 'logo' => '', 'stores' => [
            [
                'name' => 'La Lucila',
                'address' => 'Av. Maipú 3297 . La Lucila',
                'text_schedule' => 'Abierto de Lunes a sabados de 20 a 4 hs.',
                'lat' => '-34.504287',
                'lon' => '-58.493568',
                'delivery_times' => '[{"start_day":"0","start_hour":"11","start_min":"0","end_day":"6","end_hour":"24","end_min":"0"}]',
                'max_persons' => 9,
                'max_persons_day' => 40,
            ],
            [
                'name' => 'San Isidro',
                'address' => 'Av. Libertador 14656 . Acassuso.',
                'text_schedule' => 'Abierto de Lunes a sabados de 20 a 4 hs.',
                'lat' => '-34.478162',
                'lon' => '-58.4959478',
                'delivery_times' => '[{"start_day":"0","start_hour":"11","start_min":"0","end_day":"6","end_hour":"24","end_min":"0"}]',
                'max_persons' => 9,
                'max_persons_day' => 40,
            ],
        ]],
        ['name' => 'Bar Ejemplo', 'logo' => '', 'stores' => [
            [
                'name' => 'Vicente Lopez',
                'address' => 'Av. Maipú 1701 . Vicente Lopez.',
                'text_schedule' => 'Abierto de martes a domingo de 18 a 5 hs.',
                'lat' => '-34.521863',
                'lon' => '-58.4850888',
                'delivery_times' => '[{"start_day":"0","start_hour":"11","start_min":"0","end_day":"6","end_hour":"24","end_min":"0"}]',
                'max_persons' => 9,
                'max_persons_day' => 40,
            ],
            [
                'name' => 'La Lucila',
                'address' => 'Av. Maipú 3599 . La Lucila.',
                'text_schedule' => 'Abierto de Lunes a Lunes de 19.30 a 5 hs.',
                'lat' => '-34.5025644',
                'lon' => '-58.4948942',
                'delivery_times' => '[{"start_day":"0","start_hour":"11","start_min":"0","end_day":"6","end_hour":"24","end_min":"0"}]',
                'max_persons' => 9,
                'max_persons_day' => 40,
            ],
        ]],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultCompany = App\Models\Admin\Company::firstOrCreate(['name' => 'Default']);
        foreach (self::$BRANDS as $brand) {
            $stores = $brand['stores'];
            unset($brand['stores']);
            $brand['company_id'] = $defaultCompany->id;
            $brandInstance = Brand::create($brand);
            foreach ($stores as $store) {
                $store['company_id'] = $defaultCompany->id;
                $storeInstance = new Store($store);
                $brandInstance->stores()->save($storeInstance);
            }
        }
    }
}
