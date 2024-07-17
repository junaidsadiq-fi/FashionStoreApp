<?php

namespace App\Orchid\Screens\Product;

// Other
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

// Screens
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\TextArea;

// Models
use App\Models\Tag;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;

class ProductEditScreen extends Screen {

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
    return 'Edit product';
  }

  public function commandBar(): array {
    return [
      Button::make('Update')
        ->icon('note')
        ->method('update'),

      Button::make('Remove')
        ->icon('trash')
        ->method('remove')
        ->confirm('Are you sure you want to delete this product ?'),
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
          ->title('Name')
          ->placeholder('Enter product name'),

        TextArea::make('product.description')
          ->rows(5)
          ->title('Short Description')
          ->placeholder('Enter short description'),

        Input::make('product.price')
          ->title('Price:')
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
          ->fromModel(Color::class, 'name', 'name')
          ->placeholder($this->colors->count() ? 'Select colors' : 'Please create colors first in the "Colors" section.'),

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
          ->help('Recommended aspect ratio: 0.8'),

        Input::make('product.images')
          ->type('file')
          ->title('Images')
          ->multiple()
          ->help('Recommended aspect ratio: 0.8'),
      ])
    ];
  }

  public function update(Request $request, Product $product) {

    // удаление старого изображения
    $image = $request->file('product.image');
    $image && $image_path = $this->product->image;
    $image && $image_path = str_replace(asset(''), '', $image_path);
    $image && File::exists($image_path) && File::delete($image_path);

    // загрузка нового изображения
    $image && $path = $image->store('', 'upload');
    $image && $this->product->image = asset('upload/' . $path);

    // удаление старых изображений из массива
    if ($request->file('product.images')) {
      $images = json_decode($product->images);
      $imagesCount = count($images);
      if ($imagesCount > 0) {
        foreach ($images as $image) {
          $image_from_array = str_replace(asset(''), '', $image);
          File::delete($image_from_array);
        }
      }
    }


    // загрузка новых изображений в массив
    $images = $request->file('product.images');
    $multipleImages = [];

    if ($images) {
      foreach ($images as $image) {
        if ($image) {
          $path = $image->store('', 'upload');
          $multipleImages[] = asset('upload/' . $path);
          $this->product->images = json_encode($multipleImages);
        }
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
    Alert::info('You have successfully updated the product.');
    return redirect()->route('platform.product.list');
  }

  public function remove(Product $product) {

    // Delete single image
    $image_path = $product->image;
    $image_path = str_replace(asset(''), '', $image_path);
    $images = json_decode($product->images);
    $imagesCount = count($images);
    File::exists($image_path) && File::delete($image_path);

    // Delete multiple images
    if ($imagesCount > 0) {
      foreach ($images as $image) {
        $image_from_array = str_replace(asset(''), '', $image);
        File::delete($image_from_array);
      }
    }

    $this->product->delete();
    Alert::info('You have successfully deleted the product.');
    return redirect()->route('platform.product.list');
  }
}
