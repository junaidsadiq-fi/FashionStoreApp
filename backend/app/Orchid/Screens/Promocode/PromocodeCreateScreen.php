<?php

namespace App\Orchid\Screens\Promocode;

use App\Models\Promocode;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;

class PromocodeCreateScreen extends Screen {
  public $promocode;

  public function query(Promocode $promocode): iterable {
    return [
      'promocode' => $promocode
    ];
  }

  public function name(): ?string {
    return 'Creating a new promocode';
  }

  public function commandBar(): iterable {
    return [
      Button::make('Create promocode')
        ->icon('pencil')
        ->method('create')
    ];
  }

  public function layout(): iterable {
    return [
      Layout::rows([
        Input::make('promocode.code')
          ->type('text')
          ->required()
          ->title('Code')
          ->placeholder('Enter code'),

        Input::make('promocode.discount')
          ->type('number')
          ->required()
          ->title('Discount')
          ->placeholder('Enter discount in %'),

        DateTimer::make('promocode.expires_at')
          ->title('Expires at')
          // ->required()
          ->format('Y-m-d')
      ])
    ];
  }

  public function create(Request $request) {

    $request->validate([
      'promocode.code' => 'required|unique:promocodes,code,' . $this->promocode->id,
      'promocode.discount' => 'required|numeric|min:1|max:100',
      // 'promocode.expires_at' => 'required',
    ]);


    $this->promocode->code = $request->input('promocode.code');
    $this->promocode->discount = $request->input('promocode.discount');
    $this->promocode->expires_at = $request->input('promocode.expires_at');

    $this->promocode->save();
    Alert::info('You have successfully created the promocode.');
    return redirect()->route('platform.promocode.list');
  }
}
