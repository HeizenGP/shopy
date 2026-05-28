<?php

namespace App\Helpers;

use App\Modules\Settings\Infrastructure\Database\Models\SettingEloquent;
use Illuminate\Support\Facades\Cache;

class ShopHelper
{
    public const CACHE_KEY = 'shop_settings_all';

    /**
     * Get all settings from database (cached).
     *
     * @return array
     */
    public static function all(): array
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            try {
                return SettingEloquent::pluck('value', 'key')->toArray();
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    /**
     * Get a setting by key, with default and config fallback.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getSetting(string $key, mixed $default = null): mixed
    {
        $settings = self::all();

        // Intercept color keys to apply predefined themes if set
        if (str_starts_with($key, '--color-admin-')) {
            $theme = $settings['color_theme_admin'] ?? config('shop.theme_admin') ?? 'slate_corporate';
            if ($theme !== 'custom') {
                $themeColors = config("shop.color_themes_admin.{$theme}.colors");
                if ($themeColors && isset($themeColors[$key])) {
                    return $themeColors[$key];
                }
            }
        }

        if (str_starts_with($key, '--color-client-')) {
            $theme = $settings['color_theme_web'] ?? config('shop.theme_web') ?? 'indigo_imperial';
            if ($theme !== 'custom') {
                $themeColors = config("shop.color_themes_client.{$theme}.colors");
                if ($themeColors && isset($themeColors[$key])) {
                    return $themeColors[$key];
                }
            }
        }

        if (array_key_exists($key, $settings)) {
            return $settings[$key];
        }

        // Fallback to config
        $configKey = self::mapDatabaseKeyToConfigKey($key);
        if ($configKey && config()->has("shop.{$configKey}")) {
            return config("shop.{$configKey}");
        }

        return $default;
    }

    /**
     * Get client side dynamic color settings.
     *
     * @param string $name
     * @return string|null
     */
    public static function getColor(string $name): ?string
    {
        return self::getSetting("--color-{$name}");
    }

    /**
     * Clear the cache.
     *
     * @return void
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Maps database keys to their equivalent key in config/shop.php.
     *
     * @param string $key
     * @return string|null
     */
    private static function mapDatabaseKeyToConfigKey(string $key): ?string
    {
        $map = [
            'shop_name' => 'name',
            'shop_email' => 'email',
            'shop_phone' => 'phone',
            'shop_description' => 'description',
            'shop_currency' => 'currency',
            'shop_currency_symbol' => 'currency_symbol',
            'shop_tax_rate' => 'tax_rate',
            'shipping_free_threshold' => 'shipping_free_threshold',
            'timezone' => 'timezone',
            'logo_url' => 'logo.url',
            'favicon_url' => 'favicon',
            'banner_url' => 'banner',
            'color_theme_admin' => 'theme_admin',
            'color_theme_web' => 'theme_web',
            '--color-client-page' => 'colors_client.page_bg',
            '--color-client-primary' => 'colors_client.primary',
            '--color-client-surface' => 'colors_client.surface',
            '--color-client-surface-alt' => 'colors_client.surface_alt',
            '--color-client-text' => 'colors_client.text',
            '--color-client-muted' => 'colors_client.muted',
            '--color-admin-sidebar' => 'colors_admin.sidebar_bg',
            '--color-admin-primary' => 'colors_admin.primary',
            '--color-admin-container-bg' => 'colors_admin.container_bg',
            '--color-admin-page-bg' => 'colors_admin.page_bg',
            'maintenance_mode' => 'maintenance_mode',
            'maintenance_message' => 'maintenance_message',
            'reviews_moderation' => 'reviews_moderation',
            'orders_auto_email' => 'orders_auto_email',
            'wishlist_enabled' => 'wishlist_enabled',
        ];

        return $map[$key] ?? null;
    }
}
