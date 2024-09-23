<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stones extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id', 'item_id', 'stone_type', 'stone_weight', 'stone_weight_exact', 'stone_shape',
        'stone_color', 'stones_quantity', 'stone_clarity', 'stone_certified', 'certification_number', 'certified_by', 'certification_picture',
    ];

    public function lotItems(): BelongsTo
    {
        return $this->belongsTo(LotItems::class);
    }
}
