<?php

namespace App\Orchid\Screens\Review;

use Orchid\Screen\TD;
use App\Models\Review;
use Orchid\Screen\Screen;
use Illuminate\Support\Str;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\DropDown;

class ReviewListScreen extends Screen {

  public function query(): iterable {
    return [
      'review' => Review::paginate(10)
    ];
  }

  public function name(): ?string {
    return 'Reviews';
  }

  public function commandBar(): iterable {
    return [];
  }

  public function layout(): iterable {
    return [
      Layout::table('review', [
        TD::make('id', 'ID')
          ->cantHide()
          ->sort()
          ->render(function (Review $review) {
            return '#' . $review->id;
          }),

        TD::make('comment', 'Comment')
          ->cantHide()
          ->sort()
          ->filter(Input::make())
          ->render(function (Review $review) {
            return $review->comment;
          }),



        // user
        TD::make('user', 'User')
          ->cantHide()
          ->sort()
          ->filter(Input::make())
          ->render(function (Review $review) {
            return $review->user->name;
          }),

        // product
        TD::make('product', 'Product')
          ->cantHide()
          ->sort()
          ->filter(Input::make())
          ->render(function (Review $review) {
            return $review->product->name;
          }),

        // rating
        TD::make('rating', 'Rating')
          ->cantHide()
          ->sort()
          // center the rating
          ->align(TD::ALIGN_CENTER)
          ->filter(Input::make())
          ->render(function (Review $review) {
            return $review->rating;
          }),
      ])
    ];
  }
}
