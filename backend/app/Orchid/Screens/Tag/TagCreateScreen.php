<?php

namespace App\Orchid\Screens\Tag;

use App\Models\Tag;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;

class TagCreateScreen extends Screen {
  public $tag;

  public function query(Tag $tag): iterable {
    return [
      'tag' => $tag,
    ];
  }

  public function name(): ?string {
    return 'Creating a new tag';
  }

  public function commandBar(): array {
    return [
      Button::make('Create tag')
        ->icon('pencil')
        ->method('create'),
    ];
  }

  public function layout(): array {
    return [
      Layout::rows([
        Input::make('tag.name')
          ->required()
          ->title('Name')
          ->placeholder('Tag name'),

        Input::make('tag.image')
          ->required()
          ->type('file')
          ->title('Image')
          ->help('Image size must be 300x300 pixels and not more than 2MB'),
      ])
    ];
  }

  public function create(Request $request) {
    $request->validate([
      'tag.name' => 'required|unique:tags,name,' . $this->tag->id,
      'tag.image' => 'image|max:2048',
    ]);

    $image = $request->file('tag.image');
    $image && $path = $image->store('', 'upload');
    $image && $this->tag->image = asset('upload/' . $path);

    $this->tag->name = ucwords($request->input('tag.name'));

    $this->tag->save();
    Alert::info('You have successfully created a tag.');
    return redirect()->route('platform.tag.list');
  }
}
