<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AuctionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_id', 'lot_items_id'
    ];

    public function lotItem(): HasOne
    {
        return $this->hasOne(LotItems::class,'id', 'lot_items_id');
    }

    public function ad(): HasOne
    {
        return $this->hasOne(Ad::class);
    }
}
