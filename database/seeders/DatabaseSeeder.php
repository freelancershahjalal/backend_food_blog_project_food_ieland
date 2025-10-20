<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Truncate tables
        User::truncate();
        Category::truncate();
        Recipe::truncate();
        Blog::truncate();
        ContactMessage::truncate();

        // Re-enable constraints before seeding
        Schema::enableForeignKeyConstraints();

        // Seed tables in the correct order of dependency
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            RecipeSeeder::class,
            // BlogSeeder::class, // We'll uncomment these later
            // ContactMessageSeeder::class,
        ]);
    }
}
