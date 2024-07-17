<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model {
  use AsSource;
  use HasFactory;
  use Filterable;

  protected $connection = 'mysql';
  protected $table = 'orders';

  protected $fillable = [
    'total_price',
    'status',
    'delivery',
    'discount',
    'products',
    'full_name',

  ];

  protected $allowedFilters = [
    'full_name'       => Like::class,
    'phone_number'      => Like::class,
    'total_price'  => Like::class,
    'order_status' => Like::class,
  ];

  protected $casts = [
    'products' => 'array',
  ];

  protected $allowedSorts = [
    'id',
    'full_name',
    'phone_number',
    'total_price',
    'order_status',
  ];
}
