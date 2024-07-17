<?php

namespace App\Orchid\Screens\Carousel;

use App\Models\Slide;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;

class SlideCreateScreen extends Screen {

  public $slide;

  public function query(Slide $slide): iterable {
    return [
      'slide' => $slide,
    ];
  }

  public function name(): ?string {
    return 'Creating a new slide';
  }

  public function commandBar(): iterable {
    return [
      Button::make('Create slide')
        ->icon('pencil')
        ->method('create')
    ];
  }

  public function layout(): iterable {
    return [
      Layout::rows([
        Input::make('slide.title_line_1')
          ->required()
          ->placeholder('Title line 1')
          ->title('Title Line 1'),

        Input::make('slide.title_line_2')
          ->required()
          ->placeholder('Title line 2')
          ->title('Title Line 2'),

        Input::make('slide.button_text')
          ->required()
          ->placeholder('Button text')
          ->title('Button Text'),

        Input::make('slide.image')
          ->required()
          ->type('file')
          ->title('Image'),
      ]),
    ];
  }

  public function create(Request $request) {

    $request->validate([
      'slide.title_line_1' => 'required',
      'slide.title_line_2' => 'required',
      'slide.button_text' => 'required',
      'slide.image' => 'required|image|max:2048',
    ]);

    $image = $request->file('slide.image');
    $image && $path = $image->store('', 'upload');
    $image && $this->slide->image = asset('upload/' . $path);

    $this->slide->title_line_1 = ucwords($request->input('slide.title_line_1'));
    $this->slide->title_line_2 = ucwords($request->input('slide.title_line_2'));
    $this->slide->button_text = strtoupper($request->input('slide.button_text'));

    $this->slide->save();
    Alert::info('You have successfully created a slide.');
    return redirect()->route('platform.slide.list');
  }
}
