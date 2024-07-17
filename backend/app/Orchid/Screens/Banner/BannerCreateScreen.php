<?php

namespace App\Orchid\Screens\Banner;

use App\Models\Banner;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;

class BannerCreateScreen extends Screen {

  public $banner;

  public function query(Banner $banner): iterable {
    return [
      'banner' => $banner,
    ];
  }

  public function name(): ?string {
    return 'Creating a new banner';
  }

  public function commandBar(): array {
    return [
      Button::make('Create banner')
        ->icon('pencil')
        ->method('create')
    ];
  }

  public function layout(): iterable {
    return [
      Layout::rows([
        Input::make('banner.title_line_1')
          ->required()
          ->placeholder('Title Line 1')
          ->title('Title Line 1'),

        Input::make('banner.title_line_2')
          ->required()
          ->placeholder('Title Line 2')
          ->title('Title Line 2'),

        Input::make('banner.button_text')
          ->required()
          ->placeholder('Button Text')
          ->title('Button Text'),

        Input::make('banner.image')
          ->required()
          ->type('file')
          ->title('Image'),
      ]),
    ];
  }

  public function create(Request $request) {

    $request->validate([
      'banner.title_line_1' => 'required',
      'banner.title_line_2' => 'required',
      'banner.button_text' => 'required',
      'banner.image' => 'required|image|max:2048',
    ]);

    $image = $request->file('banner.image');
    $image && $path = $image->store('', 'upload');
    $image && $this->banner->image = asset('upload/' . $path);

    $this->banner->title_line_1 = ucwords($request->input('banner.title_line_1'));
    $this->banner->title_line_2 = ucwords($request->input('banner.title_line_2'));
    $this->banner->button_text = strtoupper($request->input('banner.button_text'));

    $this->banner->save();
    Alert::info('You have successfully created a banner.');
    return redirect()->route('platform.banner.list');
  }
}
