<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            'public/Banner-1.png',
            'public/Banner-2.png',
            'public/Banner-3.png',
            'public/Banner-4.png'
        ];

        foreach ($sliders as $slider) {
            Slider::create([
                'image'=>$slider
            ]);
        }
    }
}
