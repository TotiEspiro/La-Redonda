<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'frequency',
        'card_holder',
        'card_last_four',
        'email',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedFrequencyAttribute()
    {
        $frequencies = [
            'once' => 'Única',
            'weekly' => 'Semanal',
            'biweekly' => 'Quincenal',
            'monthly' => 'Mensual'
        ];

        return $frequencies[$this->frequency] ?? $this->frequency;
    }
}