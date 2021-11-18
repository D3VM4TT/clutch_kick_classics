<?php

namespace App\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {

        $menu = $event->getMenu();

        $menu['sales']
            ->addChild('competition_entries', [
                'route' => 'app_admin_order_item_unit_index',
            ])
            ->setLabel('Competition Entries')
            ->setLabelAttribute('icon', 'star');

    }
}