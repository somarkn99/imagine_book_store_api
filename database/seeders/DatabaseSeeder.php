<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Book;
use App\Models\Cart;
use Database\Seeders\Roles\AdminSeeder;
use Database\Seeders\Roles\PermissionsSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Book::factory(10)->create();
        Cart::factory(5)->create();
        $this->call(PermissionsSeeder::class);
        $this->call(AdminSeeder::class);
    }
}
