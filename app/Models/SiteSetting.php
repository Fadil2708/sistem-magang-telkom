<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('site_settings'));
        static::deleted(fn () => Cache::forget('site_settings'));
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        return static::getCachedAll()[$key] ?? $default;
    }

    public static function getValues(array $keys): array
    {
        return array_intersect_key(static::getCachedAll(), array_flip($keys));
    }

    protected static function getCachedAll(): array
    {
        return Cache::remember('site_settings', 3600, function () {
            return static::pluck('value', 'key')->all();
        });
    }
}
