<?php

namespace App\Orchid\Screens\Color;

use App\Models\Color;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class ColorCreateScreen extends Screen {
  public $color;

  public function query(Color $color): iterable {
    return [
      'color' => $color,
    ];
  }

  public function name(): ?string {
    return 'Creating a new color';
  }

  public function commandBar(): iterable {
    return [
      Button::make('Create color')
        ->icon('pencil')
        ->method('create')
    ];
  }

  public function layout(): iterable {
    return [
      Layout::rows([

        Input::make('color.name')
          ->title('Name')
          ->required()
          ->placeholder('Color name'),

        Input::make('color.hex')
          ->type('color')
          ->required()
          ->title('Color')
          ->value('#563d7c'),
      ]),
    ];
  }

  public function create(Request $request) {

    $request->validate([
      'color.name' => 'required|unique:colors,name,' . $this->color->id,
      'color.hex' => 'required|unique:colors,hex,' . $this->color->id,
    ]);

    $this->color->name = ucwords($request->input('color.name'));
    $this->color->hex = $request->input('color.hex');

    $this->color->save();
    Alert::info('You have successfully created the color.');
    return redirect()->route('platform.color.list');
  }
}
