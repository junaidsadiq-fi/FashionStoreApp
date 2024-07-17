<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller {

  public function index() {
    $orders = Order::all();
    return $orders;
  }

  public function create() {
    //
  }

  public function store(Request $request) {
    $order = new Order;
    $data = $request->json()->all();

    $email = $data['email'];
    $address = $data['address'];
    $products = $data['products'];
    $discount = $data['discount'];
    $full_name = $data['full_name'];
    $total_price = $data['total_price'];
    $order_status = $data['order_status'];
    $phone_number = $data['phone_number'];
    $delivery_price = $data['delivery_price'];
    $payment_status = $data['payment_status'];
    $subtotal_price = $data['subtotal_price'];

    $order->email = $email;
    $order->address = $address;
    $order->discount = $discount;
    $order->products = $products;
    $order->full_name = $full_name;
    $order->total_price = $total_price;
    $order->order_status = $order_status;
    $order->phone_number = $phone_number;
    $order->delivery_price = $delivery_price;
    $order->payment_status = $payment_status;
    $order->subtotal_price = $subtotal_price;

    $order->save();

    return response()->json(['status' => 'success', 'data' => $data]);
  }

  public function show(Order $order) {
    //
  }

  public function edit(Order $order) {
    //
  }

  public function update(Request $request, Order $order) {
    //
  }

  public function destroy(Order $order) {
    //
  }
}
