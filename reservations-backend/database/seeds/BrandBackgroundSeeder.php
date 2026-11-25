<?php

use App\Models\Admin\Brand;
use App\Models\Admin\BrandBackground;
use App\Models\Admin\Company;
use Illuminate\Database\Seeder;

/**
 * Fondos de ejemplo. Se puede ejecutar más de una vez sin duplicar.
 */
class BrandBackgroundSeeder extends Seeder
{
    public static $BACKGROUNDS = [
        'Komenza' => ['02.jpg', '03.jpg', '04.jpg'],
        'Bar Ejemplo' => ['14.jpg', '15.jpg', '22.jpg'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::where('name', 'Default')->firstOrFail();
        foreach (self::$BACKGROUNDS as $brandName => $files) {
            $brand = Brand::where('company_id', $company->id)->where('name', $brandName)->first();
            if ($brand == null) {
                continue;
            }
            $directory = 'img/brand/' . $brand->id;
            if (!is_dir(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }
            foreach ($files as $file) {
                $image = $directory . '/seed_' . $file;
                if (!copy(database_path('seeds/backgrounds/' . $file), public_path($image))) {
                    throw new RuntimeException('No se pudo copiar el fondo ' . $file);
                }
                BrandBackground::firstOrCreate(['brand_id' => $brand->id, 'image' => $image]);
            }
        }
    }
}
