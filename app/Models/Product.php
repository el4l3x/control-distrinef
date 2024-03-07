<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "nombre",
        "gfc",
        "climahorro",
        "ahorraclima",
        "expertclima",
        "tucalentadoreconomico",
    ];

    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($product) {
            $web = new \Spekulatius\PHPScraper\PHPScraper;

            if ($product->gfc_price == null || $product->updated_at < now()->toDateString()) {
                $web->go($product->gfc);
                $string = $web->filter("//div[@class='current-price']//span[@class='price_with_tax price_pvp']")->text();
                $string = Str::remove('€', $string);
                $product->gfc_price = floatval(Str::replace(',', '.', $string));                
            }

            if ($product->climahorro_price == null || $product->updated_at < now()->toDateString()) {
                $web->go($product->climahorro);
                $string = $web->filter("//*[@class='product-price current-price-value']")->text();
                $string = Str::remove('€', $string);
                $product->climahorro_price = floatval(Str::replace(',', '.', $string));
            }

            if ($product->ahorraclima_price == null || $product->updated_at < now()->toDateString()) {
                $web->go($product->ahorraclima);
                $string = $web->filter("//div[@class='current-price']//span[@class='price']")->text();
                $string = Str::remove('€', $string);
                $product->ahorraclima_price = floatval(Str::replace(',', '.', $string));
            }

            if ($product->expertclima_price == null || $product->updated_at < now()->toDateString()) {
                $web->go($product->expertclima);
                $string = $web->filter("//div[@class='current-price']//span[@class='current-price-value']")->text();
                $string = Str::remove('€', $string);
                $product->expertclima_price = floatval(Str::replace(',', '.', $string));
            }

            if ($product->tucalentadoreconomico_price == null || $product->updated_at < now()->toDateString()) {
                $web->go($product->tucalentadoreconomico);
                $string = $web->filter("//div[@class='current-price']//span[@itemprop='price']")->text();
                $string = Str::remove('€', $string);
                $product->tucalentadoreconomico_price = floatval(Str::replace(',', '.', $string));
            }

            $product->save();
            
        });
    }

}
