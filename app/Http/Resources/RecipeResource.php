<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'ingredients' => $this->ingredients,
            'steps' => $this->steps,
            'rating' => $this->rating,
            
            // --- THE CRITICAL FIXES ---
            'time' => '30 Minutes', // We can add a real 'time' column to the DB later
            
            // Get the URL of the first image in the 'recipes' collection
            'imageUrl' => $this->getFirstMediaUrl('recipes'), 
            
            // Include user and category data if it's loaded
            'user' => new UserResource($this->whenLoaded('user')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            
            'created_at' => $this->created_at,
        ];
    }
}
