<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name'=>'Elektronik',
                'icon'=>'category/elektronik.webp',
                'childs'=>['Microwave', 'TV']
            ],
            [
                'name'=>'Fashion Pria',
                'icon'=>'category/pakaian-pria.webp',
                'childs'=>['Kemeja', 'Jam']
            ],
            [
                'name'=>'Fashion Wanita',
                'icon'=>'category/pakaian-wanita.webp',
                'childs'=>['Dress', 'Tas']
            ],
            [
                'name'=>'Handphone',
                'icon'=>'category/Handphone.webp',
                'childs'=>['Handphone', 'Anti Gores']
            ],
            [
                'name'=>'Komputer & Laptop',
                'icon'=>'category/komputer-dan-laptop.webp',
                'childs'=>['Keyboard', 'Laptop']
            ],
            [
                'name'=>'Makanan & Minuman',
                'icon'=>'category/makanan-dan-minum.webp',
                'childs'=>['Makanan', 'Minuman']
            ],
        ];
        foreach ($categories as $categoryPayload) {
            $category = Category::create([
                'slug'=>Str::slug($categoryPayload['name']),
                'name'=>$categoryPayload['name'],
                'icon'=>$categoryPayload['icon'],
            ]);

            foreach ($categoryPayload['childs'] as $child) {
                $category->childs()->create([
                    'slug'=>Str::slug($child),
                    'name'=>$child,
                ]);
            }
        }
    }
}
