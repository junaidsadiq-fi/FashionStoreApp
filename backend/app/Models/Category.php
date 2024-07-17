<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Filterable;
use Orchid\Attachment\Attachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model {
  use AsSource;
  use HasFactory;
  use Filterable;
  use Attachable;

  protected $connection = 'mysql';
  protected $table = 'categories';

  protected $fillable = [
    'image',
    'name',
    'tags',
  ];

  protected $allowedSorts = [
    'id',
    'name',
    'created_at',
    'updated_at',
  ];

  protected $allowedFilters = [
    'id'        => Like::class,
    'name'       => Like::class,
    'created_at' => Like::class,
    'updated_at' => Like::class,
  ];

  protected $casts = [
    'tags' => 'array',
  ];
}
