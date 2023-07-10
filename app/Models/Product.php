<?php

namespace App\Models;

use App\Models\Scopes\StoreScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Product extends Model
{
    use HasFactory; // SoftDeletes;


    protected $fillable = [
        'name', 'description', 'slug', 'price', 'compare_price', 'image',
        'category_id', 'store_id', 'status',
    ];

    // this use if make json response, will hide the fields that inside $hidden from json
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    // for return Accessor and append for it in data in json
    protected $appends = [
        // for return Accessor image_url and append for it in data in json
        'image_url',
    ];

    public function scopeActive(Builder $builder)
    {
        $builder->where('status', '=', 'active');
    }

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

        // this's event
        static::creating(function (Product $product) {
            $product->slug = Str::slug($product->name);
        });
    }


    // Accessors

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return "https://www.incathlab.com/images/products/default_product.png";
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }

    public function getSalPercentAttribute()
    {
        if (!$this->compare_price) {
            return 0;
        }

        return number_format(100 - (100 * $this->price / $this->compare_price), 1);
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        // array merge work override with second array if were the same key and if was there new key in array filters will added to array options
        $options = array_merge([
            'store_id' => null,
            'category_id' => null,
            'tag_id' => null,
            'status' => 'active',
        ], $filters);

        $builder->when($options['status'], function ($query, $status) {
            $query->where('status', $status);
        });

        $builder->when($options['store_id'], function ($builder, $value) {
            $builder->where('store_id', $value);
        });

        $builder->when($options['category_id'], function ($builder, $value) {
            $builder->where('category_id', $value);
        });

        $builder->when($options['tag_id'], function ($builder, $value) {


            $builder->whereExists(function ($quey) use ($value) {
                $quey->select(1)
                    ->from('product_tag')
                    ->whereRaw('product_id = products.id')
                    ->where('tag_id', $value);
            });

            // other way
            // $builder->whereRaw('id in (select product_id from product_tag where tag_id=?)', [$value]);

            // //other way and this's best as performance and fast
            // $builder->whereRaw('EXISTS (select 1 from product_tag where tag_id=? and product_id = products.id)', [$value]);

            // other way
            // this's will return all product that has tags regardless of tag and possible rows at tags table
            // and has I give it relationship name in this model
            $builder->whereHas('tags', function ($builder, $value) {
                // here often id be tags tale id
                $builder->where('id', $value);
            });
        });
    }
}
