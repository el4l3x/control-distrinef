<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Competitor extends Model
{
    use HasFactory;

    protected $fillable = [
        "nombre",
    ];

    public function productos() : BelongsToMany {
        return $this->belongsToMany(Product::class, 'competitor_product',);
    }
}
