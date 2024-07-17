<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller {

  public function index() {
    $slides = Slide::all();
    return $slides;
  }

  public function create() {
    //
  }

  public function store(Request $request) {
    //
  }

  public function show(Slide $slide) {
    //
  }

  public function edit(Slide $slide) {
    //
  }

  public function update(Request $request, Slide $slide) {
    //
  }

  public function destroy(Slide $slide) {
    //
  }
}
