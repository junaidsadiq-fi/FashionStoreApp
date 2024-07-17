<?php

namespace App\Orchid\Screens\Carousel;

use App\Models\Slide;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\File;

class SlideEditScreen extends Screen {

  public $slide;

  public function query(Slide $slide): iterable {
    return [
      'slide' => $slide,
    ];
  }

  public function name(): ?string {
    return 'Edit slide';
  }

  public function commandBar(): array {
    return [
      Button::make('Update')
        ->icon('note')
        ->method('update'),

      Button::make('Remove')
        ->icon('trash')
        ->method('remove')
        ->confirm('Are you sure you want to delete this slide ?')
    ];
  }

  public function layout(): iterable {
    return [
      Layout::rows([
        Input::make('slide.title_line_1')
          ->placeholder('Title line 1')
          ->title('Title Line 1'),

        Input::make('slide.title_line_2')
          ->placeholder('Title line 2')
          ->title('Title Line 2'),

        Input::make('slide.button_text')
          ->placeholder('Button text')
          ->title('Button Text'),

        Input::make('slide.image')
          ->type('file')
          ->title('Image'),
      ]),
    ];
  }

  public function update(Request $request) {

    $request->validate([
      'slide.title_line_1' => 'required',
      'slide.title_line_2' => 'required',
      'slide.button_text' => 'required',
      'slide.image' => 'image|max:2048',
    ]);

    $image = $request->file('slide.image');
    $image && $image_path = $this->slide->image;
    $image && $image_path = str_replace(asset(''), '', $image_path);
    $image && File::exists($image_path) && File::delete($image_path);
    $image && $path = $image->store('', 'upload');
    $image && $this->slide->image = asset('upload/' . $path);

    $this->slide->title_line_1 = ucwords($request->input('slide.title_line_1'));
    $this->slide->title_line_2 = ucwords($request->input('slide.title_line_2'));
    $this->slide->button_text = strtoupper($request->input('slide.button_text'));

    $this->slide->save();
    Alert::info('You have successfully updated the slide.');
    return redirect()->route('platform.slide.list');
  }

  public function remove(Slide $slide) {
    $image_path = $this->slide->image;
    $image_path = str_replace(asset(''), '', $image_path);
    File::exists($image_path) && File::delete($image_path);

    $slide->delete();
    Alert::info('You have successfully deleted the slide.');
    return redirect()->route('platform.slide.list');
  }
}
