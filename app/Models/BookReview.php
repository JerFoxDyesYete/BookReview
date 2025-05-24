<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_title',
        'author',
        'review',
        'rating',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}