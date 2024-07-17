<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model {
  use AsSource;
  use Filterable;
  use HasFactory;

  protected $connection = 'mysql';
  protected $table = 'tags';

  protected $fillable = [
    'name',
    'image',
  ];

  protected $allowedSorts = [
    'id',
    'name',
    'updated_at',
  ];

  protected $allowedFilters = [
    'name'       => Like::class,
    'updated_at' => Like::class,
  ];
}
