<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'url'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->url) && !empty($model->url_link)) {
                $model->url = $model->url_link; // Gunakan URL dari url_link
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
