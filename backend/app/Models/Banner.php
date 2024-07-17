<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model {
  use HasFactory;
  use Filterable;
  use AsSource;

  protected $connection = 'mysql';
  protected $table = 'banners';

  protected $fillable = [
    'image',
    'button_text',
    'title_line_1',
    'title_line_2',
  ];

  protected $allowedSorts = [
    'id',
    'button_text',
    'title_line_1',
    'title_line_2',
    'updated_at',
  ];

  protected $allowedFilters = [
    'id'                => Like::class,
    'button_text'       => Like::class,
    'title_line_1'      => Like::class,
    'title_line_2'  => Like::class,
  ];
}
