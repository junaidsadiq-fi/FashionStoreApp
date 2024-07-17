<?php

namespace App\Orchid\Screens\category;

use App\Models\Tag;
use App\Models\Category;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\File;

class CategoryEditScreen extends Screen {

  public $category;

  public function query(Category $category): iterable {
    return [
      'category' => $category,
    ];
  }

  public function name(): ?string {
    return 'Edit category';
  }

  public function commandBar(): array {
    return [
      Button::make('Update')
        ->icon('note')
        ->method('update'),

      Button::make('Remove')
        ->icon('trash')
        ->method('remove')
        ->confirm('Are you sure you want to delete this category ?'),
    ];
  }

  public function layout(): iterable {
    return [
      Layout::rows([
        Input::make('category.name')
          ->placeholder('Category name')
          ->title('Name'),

        Select::make('category.tags')
          ->multiple()
          ->title('Tags')
          ->fromModel(Tag::class, 'name', 'name')
          ->placeholder('Select tags'),

        Input::make('category.image')
          ->type('file')
          ->title('Image'),
      ])
    ];
  }

  public function update(Request $request) {

    $request->validate([
      'category.name' => 'required|unique:tags,name,' . $this->category->id . ',id',
      'category.image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' . $this->category->id . ',id',
    ]);

    $image = $request->file('category.image');
    $image && $image_path = $this->category->image;
    $image && $image_path = str_replace(asset(''), '', $image_path);
    $image && File::exists($image_path) && File::delete($image_path);
    $image && $path = $image->store('', 'upload');
    $image && $this->category->image = asset('upload/' . $path);

    $this->category->name = ucwords($request->input('category.name'));
    $this->category->tags = $request->input('category.tags');

    $this->category->save();
    Alert::info('You have successfully created a category.');
    return redirect()->route('platform.category.list');
  }

  public function remove() {

    $image_path = $this->category->image;
    $image_path = str_replace(asset(''), '', $image_path);
    File::exists($image_path) && File::delete($image_path);

    $this->category->delete();
    Alert::info('You have successfully deleted the category.');
    return redirect()->route('platform.category.list');
  }
}
