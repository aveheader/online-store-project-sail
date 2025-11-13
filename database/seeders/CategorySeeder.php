<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $names = [
            'Смартфоны',
            'Ноутбуки',
            'Планшеты',
            'Наушники',
            'Аксессуары',
            'Умный дом',
            'Телевизоры',
            'Игровые приставки',
            'Фото и видео',
            'Сетевое оборудование',
        ];

        foreach ($names as $name) {
            Category::firstOrCreate([
                'name' => $name,
            ]);
        }
    }
}
