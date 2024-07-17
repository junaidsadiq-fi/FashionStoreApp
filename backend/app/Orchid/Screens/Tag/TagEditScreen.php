<?php

namespace App\Orchid\Screens\Tag;

use App\Models\Tag;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\File;

class TagEditScreen extends Screen {
  public $tag;

  public function query(Tag $tag): iterable {
    return [
      'tag' => $tag,
    ];
  }

  public function name(): ?string {
    return 'Edit tag';
  }

  public function commandBar(): array {
    return [
      Button::make('Update')
        ->icon('note')
        ->method('update'),

      Button::make('Remove')
        ->icon('trash')
        ->method('remove')
        ->confirm('Are you sure you want to delete this tag ?'),
    ];
  }

  public function layout(): array {
    return [
      Layout::rows([
        Input::make('tag.name')
          ->title('Name')
          ->placeholder('Edit tag'),

        Input::make('tag.image')
          ->type('file')
          ->title('Image'),
      ])
    ];
  }

  public function update(Request $request) {

    $request->validate([
      'tag.name' => 'required|unique:tags,name,' . $this->tag->id,
      'tag.image' => 'image|max:2048',
    ]);

    $image = $request->file('tag.image');
    $image && $image_path = $this->tag->image;
    $image && $image_path = str_replace(asset(''), '', $image_path);
    $image && File::exists($image_path) && File::delete($image_path);
    $image && $path = $image->store('', 'upload');
    $image && $this->tag->image = asset('upload/' . $path);

    $this->tag->name = ucwords($request->input('tag.name'));

    $this->tag->save();
    Alert::info('You have successfully updated the tag.');
    return redirect()->route('platform.tag.list');
  }

  public function remove() {

    $image_path = $this->tag->image;
    $image_path = str_replace(asset(''), '', $image_path);
    File::exists($image_path) && File::delete($image_path);

    $this->tag->delete();
    Alert::info('You have successfully deleted the tag.');
    return redirect()->route('platform.tag.list');
  }
}
