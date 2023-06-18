<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug'
    ];

    public $timestamps = false;

    public function products()
    {
        //this is when like default calling of variable

        return $this->belongsToMany(Product::class);

        // other way to content relation can this way when change default calling of variable
        // return $this->belongsToMany(
        //     Product::class,    // Related Model
        //     'product_tag',     // pivot table
        //     'tag_id',          // FK in pivot table for current Model
        //     'product_id',      // FK in pivot table for related model
        //     'id',              // PK for current model
        //     'id'               // PK for related model
        // );
    }
}
