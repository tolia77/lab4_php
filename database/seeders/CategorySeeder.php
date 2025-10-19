<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Ручні інструменти'],
            ['name' => 'Мототехніка та механізація'],
            ['name' => 'Садово-городнє обладнання'],
            ['name' => 'Запчастини та витратні матеріали'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
