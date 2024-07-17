<?php

namespace App\Orchid\Screens\Category;

use App\Models\Tag;
use App\Models\Category;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class CategoryCreateScreen extends Screen {

  public $category;

  public function query(Category $category): iterable {
    return [
      'category' => $category,
    ];
  }

  public function name(): ?string {
    return 'Create a new category';
  }

  public function commandBar(): iterable {
    return [
      Button::make('Create category')
        ->icon('pencil')
        ->method('create')
    ];
  }

  public function layout(): iterable {
    return [
      Layout::rows([

        Input::make('category.name')
          ->required()
          ->placeholder('Category name')
          ->title('Name'),

        Select::make('category.tags')
          ->multiple()
          ->title('Tags')
          ->required()
          ->disabled(!Tag::all()->count())
          ->fromModel(Tag::class, 'name', 'name')
          ->placeholder(Tag::all()->count() ? 'Select tags' : 'Please create tags first in the "Tags" section.'),

        Input::make('category.image')
          ->required()
          ->type('file')
          ->title('Image'),
      ])
    ];
  }

  public function create(Request $request) {

    $request->validate([
      'category.name' => 'required|min:3|max:225',
      'category.image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $image = $request->file('category.image');
    $image && $path = $image->store('', 'upload');
    $image && $this->category->image = asset('upload/' . $path);

    $this->category->name = $request->input('category.name');
    $this->category->tags = $request->input('category.tags');

    $this->category->save();
    Alert::info('You have successfully created a category.');
    return redirect()->route('platform.category.list');
  }
}
