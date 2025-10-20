<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory; // <-- ADD THIS LINE

    
    protected $fillable = ['name', 'slug', 'type'];
}
