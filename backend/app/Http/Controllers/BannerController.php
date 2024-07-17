<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller {

  public function index() {
    $banners = Banner::all();
    return $banners;
  }

  public function create() {
    //
  }

  public function store(Request $request) {
    //
  }

  public function show(Banner $banner) {
    //
  }

  public function edit(Banner $banner) {
    //
  }

  public function update(Request $request, Banner $banner) {
    //
  }

  public function destroy(Banner $banner) {
    //
  }
}
