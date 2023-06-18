<?php

namespace App\Models;

use App\Models\Scopes\StoreScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'name', 'description', 'slug', 'price', 'compare_price', 'image',
        'category_id', 'store_id', 'status',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,    // Related Model
            'product_tag', // pivot table
            'product_id',  // FK in pivot table for current Model
            'tag_id',      // FK in pivot table for related model
            'id',          // PK for current model
            'id'           // PK for related model
        );
    }

    protected static function booted()
    {

        static::addGlobalScope('store', new StoreScope());

        //other way to add global scope
        // static::addGlobalScope('store', function (Builder $builder) {
        //     $user = Auth::user();
        //     if ($user->store_id) {
        //         $builder->where('store_id', '=', $user->store_id);
        //     }
        // });


    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $builder->when($filters['name'] ?? false, function ($builder, $value) {
            $builder->where('products.name', 'LIKE', "%$value%");
        });

        $builder->when($filters['status'] ?? false, function ($builder, $value) {
            $builder->where('products.status', '=', $value);
        });
    }
}
