<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users and recipe categories
        $users = User::all();
        $categories = Category::where('type', 'recipe')->get();

        // If there are no users or categories, we can't create recipes.
        if ($users->isEmpty() || $categories->isEmpty()) {
            $this->command->info('Cannot seed recipes because no users or categories were found.');

            return;
        }

        // Create 20 random recipes using the correct factory syntax
        Recipe::factory(20)->make()->each(function ($recipe) use ($users, $categories) {
            // For each recipe being made, assign a random user and category
            $recipe->user_id = $users->random()->id;
            $recipe->category_id = $categories->random()->id;

            // Save the recipe to the database
            $recipe->save();
        });
    }
}
