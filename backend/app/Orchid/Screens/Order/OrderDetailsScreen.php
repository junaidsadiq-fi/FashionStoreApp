<?php

namespace App\Orchid\Screens\order;

use Orchid\Screen\TD;
use App\Models\Order;
use App\Models\Product;
use Orchid\Screen\Sight;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;

use Orchid\Support\Facades\Layout;

class OrderDetailsScreen extends Screen {
  public $products;
  public $order;

  public function query(Order $order): iterable {
    return [
      'order' => Order::find($order->id),
      'products' => collect($order->products)->mapInto(Product::class),
    ];
  }

  public function name(): ?string {
    return 'Order Details';
  }

  public function commandBar(): iterable {
    return [
      Button::make('Remove')
        ->icon('trash')
        ->method('remove')
        ->confirm('Are you sure you want to delete this order ?'),
    ];
  }

  public function layout(): iterable {

    return [
      Layout::legend('order', [
        Sight::make('full_name', 'Full Name'),
        Sight::make('email', 'Email'),
        Sight::make('phone_number', 'Phone Number'),
        Sight::make('address', 'Address'),
        Sight::make('total_price', 'Total Price')->render(function () {
          return '$ ' . $this->order->total_price;
        }),
        Sight::make('subtotal_price', 'Subtotal Price')->render(function () {
          return '$ ' . $this->order->subtotal_price;
        }),
        Sight::make('delivery_price', 'Delivery Price')->render(function () {
          return '$ ' . $this->order->delivery_price;
        }),
        Sight::make('discount', 'Discount')->render(function () {
          return '$ ' . $this->order->discount;
        }),
        Sight::make('payment_status', 'Payment Status')->render(function (Order $order) {
          return '<i class="text-success">●</i> ' . ucfirst($order->payment_status);
        }),
        Sight::make('order_status', 'Order Status')->render(function (Order $order) {
          if ($order->order_status == 'pending') {
            return '<i class="text-warning">●</i> ' . ucfirst($order->order_status);
          }
          if ($order->order_status == 'canceled') {
            return '<i class="text-danger">●</i> ' . ucfirst($order->order_status);
          }
          if ($order->order_status == 'completed') {
            return '<i class="text-success">●</i> ' . ucfirst($order->order_status);
          }
          if ($order->order_status == 'processing') {
            return '<i class="text-info">●</i> ' . ucfirst($order->order_status);
          }
          if ($order->order_status == 'on-hold') {
            return '<i class="text-warning">●</i> ' . ucfirst($order->order_status);
          }
          if ($order->order_status == 'refunded') {
            return '<i class="text-danger">●</i> ' . ucfirst($order->order_status);
          }
        }),
        Sight::make('created_at', 'Created')->render(function (Order $order) {
          return $order->created_at->format('M j, Y H:i');
        }),
      ]),

      Layout::table('products', [
        TD::make('name', 'Name')
          ->cantHide()
          ->render(function (Product $product) {
            return ucfirst($product->name);
          }),

        TD::make('price', 'Price')
          ->cantHide()
          ->render(function (Product $product) {
            return '$ ' . $product->price;
          }),

        TD::make('color', 'Color')
          ->cantHide()
          ->align(TD::ALIGN_CENTER)
          ->render(function (Product $product) {
            return ucwords($product->color);
          }),

        TD::make('size', 'Size')
          ->cantHide()
          ->align(TD::ALIGN_CENTER)
          ->render(function (Product $product) {
            return strtoupper($product->size);
          }),

        TD::make('quantity', 'Quantity')
          ->cantHide()
          ->align(TD::ALIGN_CENTER)
          ->render(function (Product $product) {
            return $product->quantity;
          }),
      ])->title('Products in order'),
    ];
  }

  public function remove(Order $order) {
    $order->delete();
    Alert::info('You have successfully deleted the order.');
    return redirect()->route('platform.order.list');
  }
}
