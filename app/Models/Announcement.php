<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'short_description',
        'full_description',
        'image',
        'modal_id',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        return asset('storage/' . $this->image);
    }

    public function getRawImagePath()
    {
        return $this->getRawOriginal('image');
    }
}