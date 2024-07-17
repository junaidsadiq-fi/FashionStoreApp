<?php

namespace App\Orchid\Screens\Product;

use Orchid\Screen\Screen;

// Screens
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\TextArea;

// Other
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Orchid\Screen\Fields\Relation;

// Models
use App\Models\Tag;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;

class ProductCreateScreen extends Screen {
  public $tags;
  public $colors;
  public $product;
  public $categories;

  public function query(Product $product): iterable {
    return [
      'product' => $product,
      'tags' => Tag::all(),
      'colors' => Color::all(),
      'categories' => Category::all(),
    ];
  }

  public function name(): ?string {
    return 'Creating a new product';
  }

  public function commandBar(): iterable {
    return [
      Button::make('Create product')
        ->icon('pencil')
        ->method('create')
    ];
  }

  public function layout(): iterable {

    $sizes = [
      'xs'   => 'XS',
      's' => 'S',
      'm' => 'M',
      'l' => 'L',
      'xl' => 'XL',
      'xxl' => 'XXL',
    ];

    return [
      Layout::rows([
        Input::make('product.name')
          ->required()
          ->title('Name')
          ->placeholder('Enter product name'),

        TextArea::make('product.description')
          ->rows(5)
          ->required()
          ->title('Short Description')
          ->placeholder('Enter short description'),

        Input::make('product.price')
          ->title('Price:')
          ->required()
          ->mask([
            'alias' => 'currency',
            'groupSeparator' => '',
          ])
          ->placeholder('Enter price'),

        Input::make('product.old_price')
          ->title('Old price')
          ->mask(['alias' => 'currency'])
          ->placeholder('Enter old price'),

        Select::make('product.sizes')
          ->multiple()
          ->title('Sizes')
          ->options($sizes),

        Select::make('product.colors')
          ->multiple()
          ->title('Colors')
          ->disabled(!$this->colors->count())
          ->fromModel(
            Color::class,
            'name',
            'name',
            // function ($query) {
            //   // return $query->orderBy('name', 'asc');
            //   // return all objects
            //   return $query;
            // }
          ) // первый параметр - модель, второй - поле, которое будет отображаться в выпадающем списке, третий - поле, которое будет передаваться в БД
          ->placeholder($this->colors->count() ? 'Select colors' : 'Please create colors first in the "Colors" section.'),

        // Select::make('product.colors')
        //   ->options([
        //     Color::class => Color::all()->pluck('name', 'name'),
        //   ]),

        // Relation::make('product.colors')
        //   ->multiple()
        //   ->fromModel(Color::class, 'name', 'name')
        //   ->searchColumns('name', 'hex')
        //   ->chunk(10)
        //   ->title('Choose your idea'),

        Select::make('product.categories')
          ->multiple()
          ->title('Categories')
          ->disabled(!$this->categories->count())
          ->fromModel(Category::class, 'name', 'name')
          ->placeholder($this->categories->count() ? 'Select categories' : 'Please create categories first in the "Categories" section.'),

        Select::make('product.tags')
          ->disabled(!$this->tags->count())
          ->placeholder($this->tags->count() ? 'Select tags' : 'Please create tags first in the "Tags" section.')
          ->fromModel(Tag::class, ucfirst('name'), ucfirst('name'))
          ->multiple()
          ->title('Tags'),

        Select::make('product.types')
          ->options([
            'featured' => 'Featured',
            'bestseller' => 'Bestseller',
          ])
          ->multiple()
          ->title('Type')
          ->placeholder('Select type'),

        Input::make('product.image')
          ->type('file')
          ->title('Image')
          ->required(!$this->product->exists)
          ->help('Recommended aspect ratio: 0.8'),

        Input::make('product.images')
          ->type('file')
          ->title('Images')
          ->multiple()
          ->required(!$this->product->exists)
          ->help('Recommended aspect ratio: 0.8'),
      ])
    ];
  }

  public function create(Request $request) {

    $request->validate([
      'product.name' => 'required|unique:products,name',
      'product.description' => 'required',
      'product.price' => 'required',
      'product.image' => 'required|image|max:2048',
      'product.images' => 'required',
    ]);

    $image = $request->hasFile('product.image');
    $image && $path = $request->file('product.image')->store('', 'upload');
    $image && $this->product->image = asset('upload/' . $path);

    $images = $request->file('product.images');
    $multipleImages = [];
    foreach ($images as $image) {
      if ($image) {
        $path = $image->store('', 'upload');
        $multipleImages[] = asset('upload/' . $path);
        $this->product->images = json_encode($multipleImages);
      }
    }

    $this->product->name = ucfirst($request->input('product.name'));
    $this->product->description = ucfirst($request->input('product.description'));
    $this->product->price = $request->input('product.price');
    $this->product->old_price = $request->input('product.old_price');
    $this->product->sizes = $request->input('product.sizes');
    $this->product->colors = $request->input('product.colors');
    $this->product->categories = $request->input('product.categories');
    $this->product->tags = $request->input('product.tags');
    $this->product->types = $request->input('product.types');

    $this->product->save();
    Alert::info('You have successfully created the product.');
    return redirect()->route('platform.product.list');
  }
}
