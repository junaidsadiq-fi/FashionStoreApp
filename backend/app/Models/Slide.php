<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Types\Like;

use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class Slide extends Model {
  use HasFactory;
  use AsSource;
  use Filterable;

  protected $connection = 'mysql';
  protected $table = 'slides';

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
    'id'         => Like::class,
    'button_text'         => Like::class,
    'title_line_1'         => Like::class,
    'title_line_2'         => Like::class,
  ];
}
