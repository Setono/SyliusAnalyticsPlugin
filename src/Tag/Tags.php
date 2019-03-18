<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Tag;

final class Tags
{
    public const TAG_LIBRARY = 'setono_sylius_analytics_library';
    public const TAG_ADD_PAYMENT_INFO = 'setono_sylius_analytics_add_payment_info';
    public const TAG_ADD_TO_CART = 'setono_sylius_analytics_add_to_cart';
    public const TAG_LOGIN = 'setono_sylius_analytics_login';
    public const TAG_SIGN_UP = 'setono_sylius_analytics_sign_up';
    public const TAG_VIEW_ITEM = 'setono_sylius_analytics_view_item';

    private function __construct()
    {
    }
}
