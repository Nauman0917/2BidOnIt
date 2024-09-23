<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LotItemImage extends Model
{
    use HasFactory;

    protected $fillable = ['lot_items_id', 'image', 'disk', 'path'];

    protected $appends = ['image_url'];

    public function item(): BelongsTo
    {
        return $this->belongsTo(LotItems::class);
    }

    public function getImageUrlAttribute()
    {
        $disk = $this->disk ?? 's3';
        return Storage::disk($disk)->url($this->image);
    }
}
