<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model {
  use AsSource;
  use HasFactory;
  use Filterable;

  protected $connection = 'mysql';
  protected $table = 'products';
  protected $fillable = [
    'name',
    'size',
    'price',
    'tags',
    'types',
    'color',
    'image',
    'sizes',
    'images',
    'colors',
    'quantity',
    'old_price',
    'categories',
    'description',
    'is_featured',
    'is_bestseller',
  ];

  protected $allowedSorts = [
    'id',
    'name',
    'price',
    'old_price',
    'updated_at',
  ];


  protected $allowedFilters = [
    'id'         => Like::class,
    'name'       => Like::class,
    'price'      => Like::class,
    'old_price'  => Like::class,
    'updated_at' => Like::class,
  ];

  protected $casts = [
    'tags' => 'array',
    'types' => 'array',
    'sizes' => 'array',
    'images' => 'array',
    'colors' => 'array',
    'price' => 'float',
    'old_price' => 'float',
    'categories' => 'array',
    'is_featured' => 'boolean',
    'is_bestseller' => 'boolean',
  ];
}
