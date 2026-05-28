<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Helpers\ShopHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (Schema::hasTable('settings')) {
                $settings = ShopHelper::all();
                
                config([
                    'shop.name' => $settings['shop_name'] ?? config('shop.name'),
                    'shop.email' => $settings['shop_email'] ?? config('shop.email'),
                    'shop.phone' => $settings['shop_phone'] ?? config('shop.phone'),
                    'shop.description' => $settings['shop_description'] ?? config('shop.description'),
                    'shop.logo.url' => $settings['logo_url'] ?? config('shop.logo.url'),
                    'shop.favicon' => $settings['favicon_url'] ?? config('shop.favicon'),
                    'shop.banner' => $settings['banner_url'] ?? config('shop.banner'),
                    'shop.theme_admin' => $settings['color_theme_admin'] ?? config('shop.theme_admin'),
                    'shop.theme_web' => $settings['color_theme_web'] ?? config('shop.theme_web'),
                    'shop.colors_client.page_bg' => ShopHelper::getSetting('--color-client-page'),
                    'shop.colors_client.primary' => ShopHelper::getSetting('--color-client-primary'),
                    'shop.colors_client.surface' => ShopHelper::getSetting('--color-client-surface'),
                    'shop.colors_client.surface_alt' => ShopHelper::getSetting('--color-client-surface-alt'),
                    'shop.colors_client.border' => ShopHelper::getSetting('--color-client-border'),
                    'shop.colors_client.text' => ShopHelper::getSetting('--color-client-text'),
                    'shop.colors_client.muted' => ShopHelper::getSetting('--color-client-muted'),
                    'shop.colors_client.header_footer_bg' => ShopHelper::getSetting('--color-client-header-footer-bg'),
                    'shop.colors_client.card' => ShopHelper::getSetting('--color-client-card'),
                    'shop.colors_client.card_border' => ShopHelper::getSetting('--color-client-card-border'),
                    'shop.colors_admin.sidebar_bg' => ShopHelper::getSetting('--color-admin-sidebar'),
                    'shop.colors_admin.primary' => ShopHelper::getSetting('--color-admin-primary'),
                    'shop.colors_admin.container_bg' => ShopHelper::getSetting('--color-admin-container-bg'),
                    'shop.colors_admin.header_bg' => ShopHelper::getSetting('--color-admin-header-bg'),
                    'shop.colors_admin.page_bg' => ShopHelper::getSetting('--color-admin-page-bg'),
                    'shop.currency' => $settings['shop_currency'] ?? config('shop.currency'),
                    'shop.currency_symbol' => $settings['shop_currency_symbol'] ?? config('shop.currency_symbol'),
                    'shop.tax_rate' => isset($settings['shop_tax_rate']) ? (float)$settings['shop_tax_rate'] : config('shop.tax_rate'),
                    'shop.shipping_free_threshold' => isset($settings['shipping_free_threshold']) ? (float)$settings['shipping_free_threshold'] : config('shop.shipping_free_threshold'),
                    'timezone' => $settings['timezone'] ?? config('shop.timezone'),
                    'shop.maintenance_mode' => isset($settings['maintenance_mode']) ? (bool)$settings['maintenance_mode'] : config('shop.maintenance_mode'),
                    'shop.maintenance_message' => $settings['maintenance_message'] ?? config('shop.maintenance_message'),
                    'shop.reviews_moderation' => isset($settings['reviews_moderation']) ? (bool)$settings['reviews_moderation'] : config('shop.reviews_moderation'),
                    'shop.orders_auto_email' => isset($settings['orders_auto_email']) ? (bool)$settings['orders_auto_email'] : config('shop.orders_auto_email'),
                    'shop.wishlist_enabled' => isset($settings['wishlist_enabled']) ? (bool)$settings['wishlist_enabled'] : config('shop.wishlist_enabled'),
                ]);

                // Sincronizar dinámicamente con app name y timezone
                if (isset($settings['shop_name'])) {
                    config(['app.name' => $settings['shop_name']]);
                }
                if (isset($settings['timezone'])) {
                    config(['app.timezone' => $settings['timezone']]);
                    date_default_timezone_set($settings['timezone']);
                }
            }
        } catch (\Exception $e) {
            // Evitar fallas si la BD no está disponible o migrada aún
        }
    }
}
