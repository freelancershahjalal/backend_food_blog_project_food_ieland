<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;

class RecipeController extends Controller
{
    use AuthorizesRequests;


    public function index()
    {
        $recipes = Recipe::with('user:id,name', 'category:id,name')->latest()->paginate(10);
        return RecipeResource::collection($recipes); 
    }

    public function show($slug)
    {
        $recipe = Recipe::where('slug', $slug)->with('user', 'category')->firstOrFail();

        // Return the recipe data formatted by our API Resource
         return new RecipeResource($recipe);
    }

    public function store(StoreRecipeRequest $request)
    {
        if (! Gate::allows('create', Recipe::class)) {
            abort(403);
        }
        //  $this->authorize('create', Recipe::class);
        $validated = $request->validated();

        $recipe = $request->user()->recipes()->create($validated);

        if ($request->hasFile('image')) {
            $recipe->addMediaFromRequest('image')->toMediaCollection('recipes');
        }
        $recipe->load('user:id,name', 'category:id,name');

        return response()->json([
            'success' => true,
            'message' => 'Recipe created successfully.',
            'data' => $recipe->load('user:id,name', 'category:id,name'),
        ], 201);
    }

    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        // $this->authorize('update', $recipe);
        if (! Gate::allows('update', $recipe)) {
            abort(403);
        }
        $recipe->update($request->validated());

        if ($request->hasFile('image')) {
            $recipe->clearMediaCollection('recipes');
            $recipe->addMediaFromRequest('image')->toMediaCollection('recipes');
        }

        $recipe->load('user:id,name', 'category:id,name');

        return response()->json([
            'success' => true,
            'message' => 'Recipe updated successfully.',
            'data' => $recipe->load('user:id,name', 'category:id,name'),
        ]);
    }

    public function destroy(Recipe $recipe)
    {
        // $this->authorize('delete', $recipe);
        if (! Gate::allows('delete', $recipe)) {
            abort(403);
        }

        $recipe->delete();

        return response()->json([
            'success' => true,
            'message' => 'Recipe successfully deleted.',
        ]);
    }
}
