<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description', 
        'category',
        'age_range',
        'meeting_days',
        'meeting_time',
        'location',
        'contact_email',
        'contact_phone',
        'image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getCategoryNameAttribute()
    {
        $categories = [
            'jovenes' => 'Jóvenes',
            'adultos' => 'Adultos & Familias',
            'mayores' => 'Mayores',
            'especiales' => 'Grupos Especiales'
        ];

        return $categories[$this->category] ?? $this->category;
    }
}