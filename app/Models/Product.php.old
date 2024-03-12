<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
                try {
                    $web->go($product->gfc);
                    $string = $web->filter("//span[@class='current-price']//span[@class='product-price current-price-value']")->text();
                    $string = Str::remove('€', $string);
                    $product->gfc_price = floatval(Str::replace(',', '.', $string));
                } catch (\Throwable $th) {
                    Log::info("------------ Fallo el scrap de gfc ------------");
                    Log::info($th->getMessage());
                }
            }

            if ($product->climahorro_price == null || $product->updated_at < now()->toDateString()) {
                try {
                    $web->go($product->climahorro);
                    $string = $web->filter("//*[@class='product-price current-price-value']")->text();
                    $string = Str::remove('€', $string);
                    $product->climahorro_price = floatval(Str::replace(',', '.', $string));
                    $product->climahorro_percent = number_format((((($product->gfc_price - $product->climahorro_price)/$product->gfc_price))*100)*-1, 2);
                } catch (\Throwable $th) {
                    Log::info("------------ Fallo el scrap de climahorro ------------");
                    Log::info($th->getMessage());
                }
            }

            if ($product->ahorraclima_price == null || $product->updated_at < now()->toDateString()) {
                try {
                    $web->go($product->ahorraclima);
                    $string = $web->filter("//div[@class='current-price']//span[@class='price']")->text();
                    $string = Str::remove('€', $string);
                    $product->ahorraclima_price = floatval(Str::replace(',', '.', $string));
                    $product->ahorraclima_percent = number_format((((($product->gfc_price - $product->ahorraclima_price)/$product->gfc_price))*100)*-1, 2);
                } catch (\Throwable $th) {
                    Log::info("------------ Fallo el scrap de ahorraclima ------------");
                    Log::info($th->getMessage());
                }
            }

            if ($product->expertclima_price == null || $product->updated_at < now()->toDateString()) {
                try {
                    $web->go($product->expertclima);
                    $string = $web->filter("//div[@class='current-price']//span[@class='current-price-value']")->text();
                    $string = Str::remove('€', $string);
                    $product->expertclima_price = floatval(Str::replace(',', '.', $string));
                    $product->expertclima_percent = number_format((((($product->gfc_price - $product->expertclima_price)/$product->gfc_price))*100)*-1, 2);
                } catch (\Throwable $th) {
                    Log::info("------------ Fallo el scrap de expertclima ------------");
                    Log::info($th->getMessage());
                }                
            }

            if ($product->tucalentadoreconomico_price == null || $product->updated_at < now()->toDateString()) {
                try {
                    $web->go($product->tucalentadoreconomico);
                    $string = $web->filter("//div[@class='current-price']//span[@itemprop='price']")->text();
                    $string = Str::remove('€', $string);
                    $product->tucalentadoreconomico_price = floatval(Str::replace(',', '.', $string));
                    $product->tucalentadoreconomico_percent = number_format((((($product->gfc_price - $product->tucalentadoreconomico_price)/$product->gfc_price))*100)*-1, 2);
                } catch (\Throwable $th) {
                    Log::info("------------ Fallo el scrap de tucalentadoreconomico ------------");
                    Log::info($th->getMessage());
                }                
            }
            
            $product->save();
        });
    }

}
