<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promocode extends Model {
  use AsSource;
  use HasFactory;
  use Filterable;

  protected $connection = 'mysql';
  protected $table = 'promocodes';

  protected $fillable = [
    'code',
    'discount',
    'expires_at',
  ];

  protected $casts = [
    'expires_at' => 'date:M j, Y',
  ];

  protected $allowedSorts = [
    'id',
    'code',
    'discount',
    'expires_at',
    'updated_at',
  ];

  protected $allowedFilters = [
    'id'             => Like::class,
    'code'           => Like::class,
    'discount'       => Like::class,
    'expires_at'     => Like::class,
    'updated_at'     => Like::class,
  ];
}
