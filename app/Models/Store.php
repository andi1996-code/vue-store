<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Store extends Model
{

    protected $fillable = ['name', 'address','logo', 'slug','description'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($store) {
            $store->slug = Str::slug($store->name);
        });

        static::updating(function ($store) {
            $store->slug = Str::slug($store->name);
        });
    }
}
