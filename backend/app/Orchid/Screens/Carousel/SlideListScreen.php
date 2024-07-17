<?php

namespace App\Orchid\Screens\Carousel;

use Orchid\Screen\TD;
use App\Models\Slide;
use Orchid\Screen\Screen;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\DropDown;
use Illuminate\Support\Facades\File;

class SlideListScreen extends Screen {

  public $slides;

  public function query(): iterable {
    return [
      'slides' => Slide::filters()->defaultSort('updated_at', 'desc')->paginate(10),
    ];
  }

  public function name(): ?string {
    return 'Slides';
  }

  public function description(): ?string {
    return 'You can create maximum 5 slides.';
  }

  public function commandBar(): iterable {
    return [
      Link::make('Create new')
        ->icon('bs.plus-circle')
        ->route('platform.slide.create')
        ->canSee($this->slides->count() < 5),
    ];
  }

  public function layout(): iterable {
    return [
      Layout::table('slides', [
        TD::make('image', 'Image')
          ->cantHide()
          ->render(function (Slide $slide) {
            return '<img src="' . $slide->image . '" width="50" height="50" style="object-fit: cover; object-position: center;">';
          }),

        TD::make('title_line_1', 'Description')
          ->cantHide()
          ->sort()
          ->filter(Input::make())
          ->render(function (Slide $slide) {
            return Link::make(ucwords($slide->title_line_1) . ' ' . ucwords($slide->title_line_2))->route('platform.slide.edit', $slide->id);
          }),

        TD::make('button_text', 'Button Text')
          ->sort()
          ->cantHide()
          ->filter(Input::make())
          ->render(function (Slide $slide) {
            return '<div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-transform: uppercase;">' . $slide->button_text . '</div>';
          }),

        TD::make('updated_at', __('Last edit'))
          ->cantHide()
          ->sort()
          ->filter(Input::make())
          ->render(function (Slide $slide) {
            return $slide->updated_at->format('M j, Y');
          }),

        TD::make('id', 'ID')
          ->cantHide()
          ->sort()
          ->filter(Input::make())
          ->align(TD::ALIGN_CENTER)
          ->render(function (Slide $slide) {
            return '#' . $slide->id;
          }),

        TD::make(__('Actions'))
          ->cantHide()
          ->align(TD::ALIGN_CENTER)
          ->width('100px')
          ->render(fn (Slide $slide) => DropDown::make()
            ->icon('bs.three-dots-vertical')
            ->list([
              Link::make(__('Edit'))
                ->route('platform.slide.edit', $slide->id)
                ->icon('bs.pencil'),

              Button::make('Delete')
                ->icon('bs.trash3')
                ->confirm('Are you sure you want to delete this slide ?')
                ->method('remove')
                ->parameters([
                  'slide' => $slide->id,
                ]),
            ])),
      ])
    ];
  }

  public function remove(Slide $slide) {

    $image_path = $slide->image;
    $image_path = str_replace(asset(''), '', $image_path);

    if (File::exists($image_path)) {
      File::delete($image_path);
    }

    $slide->delete();

    Alert::info('You have successfully deleted the slide.');
    return redirect()->route('platform.slide.list');
  }
}
