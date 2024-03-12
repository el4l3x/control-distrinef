<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "nombre",
        "url",
        "competitor_id",
        "precio",
    ];

    /* public function competidor() : BelongsTo {
        return $this->belongsTo(Competitor::class);
    } */

    public function competidor() : BelongsToMany {
        return $this->belongsToMany(Competitor::class)->withPivot('precio', 'url');
    }
}
