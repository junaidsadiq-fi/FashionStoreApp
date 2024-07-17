<?php

namespace App\Orchid\Screens\Color;

use App\Models\Color;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class ColorEditScreen extends Screen {
  public $color;

  public function query(Color $color): iterable {
    return [
      'color' => $color,
    ];
  }

  public function name(): ?string {
    return 'Edit color';
  }

  public function commandBar(): iterable {
    return [
      Button::make('Update')
        ->icon('note')
        ->method('update'),

      Button::make('Remove')
        ->icon('trash')
        ->method('remove')
        ->confirm('Are you sure you want to delete this color ?'),
    ];
  }

  public function layout(): iterable {
    return [
      Layout::rows([
        Input::make('color.name')
          ->title('Name')
          ->placeholder('Edit color'),

        Input::make('color.hex')
          ->type('color')
          ->title('Color')
          ->value('#563d7c'),
      ]),
    ];
  }

  public function update(Request $request) {

    $request->validate([
      'color.name' => 'required|unique:colors,name,' . $this->color->id,
      'color.hex' => 'required|unique:colors,hex,' . $this->color->id,
    ]);

    $this->color->name = ucwords($request->input('color.name'));
    $this->color->hex = $request->input('color.hex');

    $this->color->save();
    Alert::info('You have successfully updated the color.');
    return redirect()->route('platform.color.list');
  }

  public function remove() {
    $this->color->delete();
    Alert::info('You have successfully deleted the color.');
    return redirect()->route('platform.color.list');
  }
}
