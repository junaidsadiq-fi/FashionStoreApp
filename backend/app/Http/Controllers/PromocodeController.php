<?php

namespace App\Http\Controllers;

use App\Models\Promocode;
use Illuminate\Http\Request;

class PromocodeController extends Controller {

  public function index() {
    $promocodes = Promocode::all();
    return $promocodes;
  }

  public function create() {
    //
  }

  public function store(Request $request) {
    //
  }

  public function show(Promocode $promocode) {
    //
  }

  public function edit(Promocode $promocode) {
    //
  }

  public function update(Request $request, Promocode $promocode) {
    //
  }

  public function destroy(Promocode $promocode) {
    //
  }
}
