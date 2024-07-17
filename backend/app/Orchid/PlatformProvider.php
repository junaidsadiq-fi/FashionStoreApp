<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;
use App\Models\Order;

class PlatformProvider extends OrchidServiceProvider {

  public function boot(Dashboard $dashboard): void {
    parent::boot($dashboard);

    // ...
  }

  public function menu(): array {
    return [
      Menu::make('Products')
        ->icon('bs.bag')
        ->title('Navigation')
        ->route('platform.product.list'),

      Menu::make('Orders')
        ->icon('bs.cart3')
        ->badge(function () {
          if (Order::where('order_status', 'pending')->count() > 0) {
            return Order::where('order_status', 'pending')->count();
          }
        })
        ->route('platform.order.list'),

      Menu::make('Promocodes')
        ->icon('bs.tag')
        ->route('platform.promocode.list'),

      Menu::make('Carousel')
        ->icon('bs.card-image')
        ->route('platform.slide.list'),

      Menu::make('Banners')
        ->icon('bs.card-image')
        ->route('platform.banner.list'),

      Menu::make('Tags')
        ->title('Details')
        ->icon('bs.tags')
        ->route('platform.tag.list'),

      Menu::make('Colors')
        ->icon('bs.palette-fill')
        ->route('platform.color.list'),

      Menu::make('Categories')
        ->icon('bs.grid')
        ->route('platform.category.list'),

      Menu::make(__('Users'))
        ->icon('bs.people')
        ->route('platform.systems.users')
        ->permission('platform.systems.users')
        ->title(__('Access Controls')),

      Menu::make(__('Roles'))
        ->icon('bs.lock')
        ->route('platform.systems.roles')
        ->permission('platform.systems.roles')
        ->divider(),
    ];
  }

  /**
   * Register permissions for the application.
   *
   * @return ItemPermission[]
   */
  public function permissions(): array {
    return [
      ItemPermission::group(__('System'))
        ->addPermission('platform.systems.roles', __('Roles'))
        ->addPermission('platform.systems.users', __('Users')),
    ];
  }
}
