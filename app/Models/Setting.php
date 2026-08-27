<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    protected static $cachedSettings = null;

    public static function getAllSettings()
    {
        if (self::$cachedSettings !== null) {
            return self::$cachedSettings;
        }

        try {
            self::$cachedSettings = Cache::remember('app_settings_all', 3600, function () {
                return self::pluck('value', 'key')->toArray();
            });
        } catch (\Throwable $e) {
            self::$cachedSettings = [];
        }

        return self::$cachedSettings;
    }

    public static function get($key, $default = null)
    {
        $settings = self::getAllSettings();
        return array_key_exists($key, $settings) ? $settings[$key] : $default;
    }

    public static function set($key, $value, $group = 'general')
    {
        self::$cachedSettings = null;
        Cache::forget('app_settings_all');
        return self::updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);
    }
}
