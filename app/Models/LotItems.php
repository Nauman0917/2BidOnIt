<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LotItems extends Model
{
    protected $fillable = [
        'user_id',
        'item_name',
        'detailed_description',
        'item_weight',
        'weight_unit',
        'item_type',
        'metal_type',
        'metal_color',
        'item_size',
        'total_gem_weight',
        'appraised_value',
        'reserve_price',
        'internalCatalogNumber',
        'startPrice',
        'minEstimate',
        'maxEstimate',
        'buyoutPrice',
        'postSalePrice',
        'serial_number',
        'bar_code_image',
        'disk',
        'path'
    ];

    public function stones(): HasMany
    {
        return $this->hasMany(Stones::class, 'item_id', 'id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(LotItemImage::class, 'lot_items_id', 'id');
    }
}
