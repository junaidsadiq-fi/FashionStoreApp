<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller {
  public function index() {
    $reviews = Review::all();
    return $reviews;

    // $reviews = Review::with('user')->get();
    // return $reviews;

    // $reviews = Review::with('user', 'product')->get();
    // return $reviews;
  }

  public function create() {
    //
  }

  public function store(Request $request) {
    $review = new Review;

    $review->comment = $request->comment;
    $review->rating = $request->rating;
    // $review->approved = $request->approved;
    $review->user_id = $request->user_id;
    $review->product_id = $request->product_id;

    // $data = $request->json()->all();
    $review->save();

    return response()->json(['status' => 'success', 'data' => $review]);
  }

  public function show(Review $review) {
    //
  }

  public function edit(Review $review) {
    //
  }

  public function update(Request $request, Review $review) {
    //
  }

  public function destroy(Review $review) {
    //
  }
}
