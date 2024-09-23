<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    protected $guarded = [];


    public function items(): BelongsTo
    {
        return $this->belongsTo(LotItems::class, 'ad_id');
    }


}
