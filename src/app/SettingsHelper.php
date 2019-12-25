<?php

namespace Vipertecpro\Settings\App;

class SettingsHelper
{
    /**
     * SettingsHelper constructor.
     */
    public function __construct()
    {
    }

    public function get($key = '', $default = null)
    {
        if (strpos($key, '*')) {
            $key = str_replace('*', '%', $key);
            $settings = Setting::where('code', 'like', $key);

            $result = [];

            foreach ($settings->get() as $item) {
                $result[$item->code] = $item->value;
            }

            if (empty($result) && $default !== null) {
                return $default;
            }

            return $result;
        }

        $setting = Setting::whereCode($key);

        $setting = $setting->first();

        if ($setting) {
            return $setting->value;
        }

        if ($default !== null) {
            return $default;
        } else {
            return '';
        }
    }

    public function has($key): bool
    {
        if (strpos($key, '*')) {
            $key = str_replace('*', '%', $key);
            return (Setting::where('code', 'like', $key)->count() > 0);
        }

        return (Setting::whereCode($key)->count() > 0);
    }
}