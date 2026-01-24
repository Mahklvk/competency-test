<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'name' => 'admin',
            'email' => 'admintoko_ku@gmail.com',
            'password' => Hash::make('admin123')
        ]);

        Brand::create([
            'name' => 'Nike',
            'logo' => 'Nike.jpg'
        ]);

        Category::create([
            'name' => 'Baju',
            'icon' => 'Baju.jpg'
        ]);

        PromoCode::create([
            'code' => 'AKUINGINKAYA',
            'discount_amount' => 100000
        ]);
        
    }
}
