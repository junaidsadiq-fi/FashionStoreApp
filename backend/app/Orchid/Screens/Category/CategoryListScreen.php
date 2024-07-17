<?php

namespace App\Orchid\Screens\category;

use Orchid\Screen\TD;
use App\Models\Category;
use Orchid\Screen\Screen;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\DropDown;
use Illuminate\Support\Facades\File;

class CategoryListScreen extends Screen {

  public function query(): iterable {
    return [
      'categories' => Category::filters()->defaultSort('created_at', 'desc')->paginate(10)
    ];
  }

  public function name(): ?string {
    return 'Categories';
  }

  public function commandBar(): iterable {
    return [
      Link::make('Create new')
        ->icon('bs.plus-circle')
        ->route('platform.category.create')
    ];
  }

  public function layout(): iterable {
    return [
      Layout::table('categories', [
        TD::make('image', 'Image')
          ->cantHide()
          ->render(function (Category $category) {
            return '<img src="' . $category->image . '" width="50" height="50" style="object-fit: cover; object-position: center;">';
          }),

        TD::make('name', 'Name')
          ->sort()
          ->cantHide()
          ->filter(Input::make())
          ->render(function (Category $category) {
            return Link::make(Str::ucfirst($category->name))->route('platform.category.edit', $category->id);
          }),

        TD::make('updated_at', __('Last edit'))
          ->sort()
          ->cantHide()
          ->filter(Input::make())
          ->render(function (Category $category) {
            return $category->updated_at->format('M j, Y');
          }),

        TD::make('id', 'ID')
          ->cantHide()
          ->sort()
          ->filter(Input::make())
          ->align(TD::ALIGN_CENTER)
          ->render(function (Category $category) {
            return '#' . $category->id;
          }),

        TD::make(__('Actions'))
          ->cantHide()
          ->align(TD::ALIGN_CENTER)
          ->width('100px')
          ->render(fn (Category $category) => DropDown::make()
            ->icon('bs.three-dots-vertical')
            ->list([
              Link::make(__('Edit'))
                ->route('platform.category.edit', $category->id)
                ->icon('bs.pencil'),
              Button::make('Delete')
                ->icon('bs.trash3')
                ->confirm('Are you sure you want to delete this category ?')
                ->method('remove')
                ->parameters([
                  'category' => $category->id,
                ]),
            ])),

      ])
    ];
  }

  public function remove(Category $category) {

    $image_path = $category->image;
    $image_path = str_replace(asset(''), '', $image_path);
    File::exists($image_path) && File::delete($image_path);

    $category->delete();
    Alert::info('You have successfully deleted the category.');
    return redirect()->route('platform.category.list');
  }
}
