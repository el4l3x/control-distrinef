<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competitor extends Model
{
    use HasFactory;

    /* public function products() : HasMany {
        return $this->hasMany(Product::class);
    } */

    public function products() : BelongsToMany {
        return $this->belongsToMany(Product::class)->withPivot('precio', 'url');
    }

    /* public function productsGfc($id) : BelongsToMany {
        return $this->belongsToMany(Product::class);
    } */
}
