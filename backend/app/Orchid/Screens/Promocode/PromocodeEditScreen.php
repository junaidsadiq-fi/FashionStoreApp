<?php

namespace App\Orchid\Screens\promocode;

use App\Models\Promocode;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;

class PromocodeEditScreen extends Screen {

  public $promocode;

  public function query(Promocode $promocode): iterable {
    return [
      'promocode' => $promocode
    ];
  }

  public function name(): ?string {
    return 'Edit promocode';
  }

  public function commandBar(): array {
    return [
      Button::make('Update')
        ->icon('note')
        ->method('update'),

      Button::make('Remove')
        ->icon('trash')
        ->method('remove')
        ->confirm('Are you sure you want to delete this promocode ?'),
    ];
  }

  public function layout(): iterable {
    return [
      Layout::rows([
        Input::make('promocode.code')
          ->type('text')
          ->title('Code')
          ->placeholder('Enter code'),

        Input::make('promocode.discount')
          ->type('number')
          ->title('Discount')
          ->placeholder('Enter discount in %'),

        DateTimer::make('promocode.expires_at')
          ->title('Expires at')
          ->format('Y-m-d')
      ])
    ];
  }

  public function update(Request $request) {

    $request->validate([
      'promocode.code' => 'required|unique:promocodes,code,' . $this->promocode->id,
      'promocode.discount' => 'required|numeric|min:1|max:100',
      // 'promocode.expires_at' => 'required',
    ]);

    $this->promocode->code = $request->input('promocode.code');
    $this->promocode->discount = $request->input('promocode.discount');
    $this->promocode->expires_at = $request->input('promocode.expires_at');

    $this->promocode->save();
    Alert::info('You have successfully updated the promocode.');
    return redirect()->route('platform.promocode.list');
  }

  public function remove() {
    $this->promocode->delete();
    Alert::info('You have successfully deleted the promocode.');
    return redirect()->route('platform.promocode.list');
  }
}
