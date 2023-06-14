<?php

namespace App\Models;

use App\Rules\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Category extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'name', 'parent_id', 'description', 'image', 'slug', 'status'
    ];

    // or
    // protected $guarded = [];

    public function scopeActive(Builder $builder)
    {
        $builder->where('status', '=', 'active');
    }

    public function scopeFilter(Builder $builder, $filters)
    {

        $builder->when($filters['name'] ?? false, function ($builder, $value) {
            $builder->where('categories.name', 'LIKE', "%{ $value }%");
        });

        $builder->when($filters['status'] ?? false, function ($builder, $value) {
            $builder->where('categories.status', '=', $value);
        });


        # other way for filtering
        // if ($filters['name'] ?? false) {
        //     $builder->where('name', 'LIKE', "{$filters['name']}");
        // }

        // if ($filters['status'] ?? false) {
        //     $builder->where('status', '=', "{$filters['status']}");
        // }
    }

    public static function rules($id = 0)
    {
        return [
            // 'name' => "required|string|min:3|max:255|unique:categories,name, $id",   Or other way
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('categories', 'name')->ignore($id),
                // function ($attribute, $value, $fails) {
                //     if (strtolower($value) == 'laravel') {
                //         $fails('The name is forbidden!');
                //     }
                // }
                // new Filter(["php", "laravel", "html"]),
                'filter:laravel, php, html'
            ],

            'parent_id' => [
                'nullable', 'exists:categories,id', 'integer'
            ],

            'image' => ['image', 'dimensions:min_width:100,min_height:100', 'max:1048576'],
            'status' => 'required|in:active,archived'
        ];
    }
}
