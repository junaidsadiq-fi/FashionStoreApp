<?php

namespace App\Orchid\Screens\banner;

use Orchid\Screen\TD;
use App\Models\Banner;
use Orchid\Screen\Screen;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\DropDown;
use Illuminate\Support\Facades\File;

class BannerListScreen extends Screen {

  public $banners;

  public function query(): iterable {
    return [
      'banners' => Banner::filters()->defaultSort('updated_at', 'desc')->paginate()
    ];
  }

  public function name(): ?string {
    return 'Banners';
  }

  public function description(): ?string {
    return 'You can create maximum 3 banners.';
  }

  public function commandBar(): iterable {
    return [
      Link::make('Create new')
        ->icon('bs.plus-circle')
        ->route('platform.banner.create')
        ->canSee($this->banners->count() < 3),
    ];
  }

  public function layout(): iterable {
    return [
      Layout::table('banners', [
        TD::make('image', 'Image')
          ->cantHide()
          ->render(function (Banner $banner) {
            return '<img src="' . $banner->image . '" width="50" height="50" style="object-fit: cover; object-position: center;">';
          }),

        TD::make('title_line_1', 'Description')
          ->cantHide()
          ->sort()
          ->filter(Input::make())
          ->render(function (Banner $banner) {
            $title1 = ucwords($banner->title_line_1);
            $title2 = ucwords($banner->title_line_2);
            return Link::make($title1 . ' ' . $title2)->route('platform.banner.edit', $banner->id);
          }),

        TD::make('button_text', 'Button Text')
          ->cantHide()
          ->sort()
          ->filter(Input::make())
          ->render(function (Banner $banner) {
            return strtoupper($banner->button_text);
          }),

        TD::make('updated_at', __('Last edit'))
          ->sort()
          ->filter(Input::make())
          ->render(function (Banner $banner) {
            return $banner->updated_at->format('M j, Y');
          }),

        TD::make('id', 'ID')
          ->cantHide()
          ->sort()
          ->filter(Input::make())
          ->align(TD::ALIGN_CENTER)
          ->render(function (Banner $banner) {
            return '#' . $banner->id;
          }),

        TD::make(__('Actions'))
          ->cantHide()
          ->align(TD::ALIGN_CENTER)
          ->width('100px')
          ->render(fn (Banner $banner) => DropDown::make()
            ->icon('bs.three-dots-vertical')
            ->list([
              Link::make(__('Edit'))
                ->route('platform.banner.edit', $banner->id)
                ->icon('bs.pencil'),

              Button::make('Delete')
                ->icon('bs.trash3')
                ->confirm('Are you sure you want to delete this banner ?')
                ->method('remove')
                ->parameters([
                  'banner' => $banner->id,
                ]),

            ])),
      ])
    ];
  }

  public function remove(Banner $banner) {
    $image_path = $banner->image;
    $image_path = str_replace(asset(''), '', $image_path);
    File::exists($image_path) && File::delete($image_path);

    $banner->delete();
    Alert::info('You have successfully deleted the banner.');
    return redirect()->route('platform.banner.list');
  }
}
