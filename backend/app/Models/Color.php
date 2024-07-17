<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;

class Color extends Model {
  use HasFactory;
  use AsSource;
  use Filterable;

  protected $connection = 'mysql';
  protected $table = 'colors';

  protected $fillable = [
    'name',
    'hex',
  ];

  protected $allowedSorts = [
    'name',
    'updated_at',
  ];

  protected $allowedFilters = [
    'name'       => Like::class,
    'updated_at' => Like::class,
  ];
}
