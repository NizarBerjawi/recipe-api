<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = Collection::make([
            // Volume
            ['code' => 'ml', 'label' => 'milliliter', 'type' => 'metric'],
            ['code' => 'l', 'label' => 'liter', 'type' => 'metric'],
            ['code' => 'dl', 'label' => 'deciliter', 'type' => 'metric'],
            ['code' => 'tsp.', 'label' => 'teaspoon', 'type' => 'imperial'],
            ['code' => 'tbsp.', 'label' => 'tablespoon', 'type' => 'imperial'],
            ['code' => 'fl oz.', 'label' => 'fluid ounce', 'type' => 'imperial'],
            ['code' => 'gill', 'label' => 'gill', 'type' => 'imperial'],
            ['code' => 'c', 'label' => 'cup', 'type' => 'imperial'],
            ['code' => 'pt', 'label' => 'pint', 'type' => 'imperial'],
            ['code' => 'qt', 'label' => 'quart', 'type' => 'imperial'],
            ['code' => 'gal', 'label' => 'gallon', 'type' => 'imperial'],
            // Weight
            ['code' => 'mg', 'label' => 'milligram', 'type' => 'metric'],
            ['code' => 'g', 'label' => 'gram', 'type' => 'metric'],
            ['code' => 'kg', 'label' => 'kilogram', 'type' => 'metric'],
            ['code' => 'lb', 'label' => 'pound', 'type' => 'imperial'],
            ['code' => 'oz', 'label' => 'ounce', 'type' => 'imperial'],
            // Length
            ['code' => 'mm', 'label' => 'millimeter', 'type' => 'metric'],
            ['code' => 'cm', 'label' => 'centimeter', 'type' => 'metric'],
            ['code' => 'm', 'label' => 'meter', 'type' => 'metric'],
            ['code' => 'in', 'label' => 'inch', 'type' => 'imperial'],
            ['code' => 'yard', 'label' => 'yard', 'type' => 'imperial'],
            // Temperature
            ['code' => '°C', 'label' => 'degree celsius', 'type' => 'imperial'],
            ['code' => '°F', 'label' => 'degree Farenheit', 'type' => 'imperial'],
        ]);

        $units->each(fn(array $data) => Unit::create($data));
    }
}
