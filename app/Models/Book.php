<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'cover',
        'author',
        'genre',
        'price',
        'stock',
    ];

    // Define a custom accessor for the 'cover' attribute
    public function getCoverAttribute($value)
    {
        // Check if the 'cover' attribute already contains 'http' or 'https'
        if (str_starts_with($value, 'http:') || str_starts_with($value, 'https:')) {
            return $value; // Return as-is
        }

        // Prepend APP_URL if 'cover' doesn't start with 'http' or 'https'
        return config('app.url').'/'.$value;
    }

    public static function filterByGenreAndAuthor($genre, $author)
    {
        $query = self::query();

        if ($genre) {
            $query->where('genre', $genre);
        }

        if ($author) {
            $query->where('author', $author);
        }

        // Select specific columns
        $query->select('id', 'title', 'cover', 'created_at');

        return $query;
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
