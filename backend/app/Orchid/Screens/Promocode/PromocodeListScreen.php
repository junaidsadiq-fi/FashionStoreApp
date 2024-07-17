<?php

namespace App\Orchid\Screens\promocode;

use Orchid\Screen\TD;
use App\Models\Promocode;
use Orchid\Screen\Screen;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Fields\Input;

class PromocodeListScreen extends Screen {

  public function query(): iterable {
    return [
      'promocodes' => Promocode::filters()->defaultSort('updated_at', 'desc')->paginate(10)
    ];
  }

  public function name(): ?string {
    return 'Promocodes';
  }

  public function commandBar(): iterable {
    return [
      Link::make('Create new')
        ->icon('bs.plus-circle')
        ->route('platform.promocode.create')
    ];
  }

  public function layout(): iterable {
    return [
      Layout::table('promocodes', [

        TD::make('code', 'Code')
          ->sort()
          ->filter(Input::make())
          ->cantHide()
          ->render(function (Promocode $promocode) {
            return Link::make(Str::ucfirst($promocode->code))->route('platform.promocode.edit', $promocode->id);
          }),

        TD::make('discount', 'Discount')
          ->cantHide()
          ->sort()
          ->filter(Input::make())
          ->render(function (Promocode $promocode) {
            return $promocode->discount . '%';
          }),

        TD::make('expires_at', 'Expiry date')
          ->filter(Input::make())
          ->sort()
          ->cantHide()
          ->render(function (Promocode $promocode) {
            return $promocode->expires_at ? $promocode->expires_at->format('M j, Y') : 'Never';
          }),

        TD::make('updated_at', __('Last edit'))
          ->cantHide()
          ->sort()
          ->filter(Input::make())
          ->render(function (Promocode $promocode) {
            return $promocode->updated_at->format('M j, Y');
          }),

        TD::make('id', 'ID')
          ->cantHide()
          ->sort()
          ->filter(Input::make())
          ->align(TD::ALIGN_CENTER)
          ->render(function (Promocode $promocode) {
            return '#' . $promocode->id;
          }),

        TD::make(__('Actions'))
          ->cantHide()
          ->align(TD::ALIGN_CENTER)
          ->width('100px')
          ->render(fn (Promocode $promocode) => DropDown::make()
            ->icon('bs.three-dots-vertical')
            ->list([
              Link::make(__('Edit'))
                ->route('platform.promocode.edit', $promocode->id)
                ->icon('bs.pencil'),

              Button::make('Delete')
                ->icon('bs.trash3')
                ->confirm('Are you sure you want to delete this promocode ?')
                ->method('remove')
                ->parameters([
                  'promocode' => $promocode->id,
                ]),

            ])),
      ])
    ];
  }

  public function remove(Promocode $promocode) {
    $promocode->delete();
    Alert::info('You have successfully deleted the promocode.');
    return redirect()->route('platform.promocode.list');
  }
}
