<?php

namespace App\Orchid\Screens\banner;

use App\Models\Banner;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\File;

class BannerEditScreen extends Screen {

  public $banner;

  public function query(Banner $banner): iterable {
    return [
      'banner' => $banner,
    ];
  }

  public function name(): ?string {
    return 'Edit banner';
  }

  public function commandBar(): array {
    return [
      Button::make('Update')
        ->icon('note')
        ->method('update'),

      Button::make('Remove')
        ->icon('trash')
        ->method('remove')
        ->confirm('Are you sure you want to delete this banner ?'),
    ];
  }

  public function layout(): iterable {
    return [
      Layout::rows([
        Input::make('banner.title_line_1')
          ->placeholder('Title Line 1')
          ->title('Title Line 1'),

        Input::make('banner.title_line_2')
          ->placeholder('Title Line 2')
          ->title('Title Line 2'),

        Input::make('banner.button_text')
          ->placeholder('Button Text')
          ->title('Button Text'),

        Input::make('banner.image')
          ->type('file')
          ->title('Image'),
      ]),
    ];
  }

  public function update(Request $request) {

    $request->validate([
      'banner.title_line_1' => 'required',
      'banner.title_line_2' => 'required',
      'banner.button_text' => 'required',
      'banner.image' => 'image|max:2048',
    ]);

    $image = $request->file('banner.image');
    $image && $image_path = $this->banner->image;
    $image && $image_path = str_replace(asset(''), '', $image_path);
    $image && File::exists($image_path) && File::delete($image_path);
    $image && $path = $image->store('', 'upload');
    $image && $this->banner->image = asset('upload/' . $path);

    $this->banner->title_line_1 = ucwords($request->input('banner.title_line_1'));
    $this->banner->title_line_2 = ucwords($request->input('banner.title_line_2'));
    $this->banner->button_text = strtoupper($request->input('banner.button_text'));

    $this->banner->save();
    Alert::info('You have successfully updated the banner.');
    return redirect()->route('platform.banner.list');
  }

  public function remove(Banner $banner) {
    $image_path = $this->banner->image;
    $image_path = str_replace(asset(''), '', $image_path);
    File::exists($image_path) && File::delete($image_path);

    $banner->delete();
    Alert::info('You have successfully deleted the banner.');
    return redirect()->route('platform.banner.list');
  }
}
