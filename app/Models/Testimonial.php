<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'role',
        'content',
        'rating',
        'avatar',
        'wedding_date',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'wedding_date' => 'date',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
