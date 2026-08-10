<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
    ];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = Cache::rememberForever("setting.{$key}", function () use ($key) {
            return static::query()->where('key', $key)->first();
        });

        if (! $setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'number' => is_numeric($setting->value) ? $setting->value + 0 : $default,
            'json' => json_decode($setting->value ?? 'null', true) ?? $default,
            default => $setting->value ?? $default,
        };
    }

    public static function setValue(string $key, mixed $value, string $type = 'string', string $group = 'general'): self
    {
        $stored = match ($type) {
            'json' => json_encode($value),
            'boolean' => $value ? '1' : '0',
            default => (string) $value,
        };

        $setting = static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $stored, 'type' => $type, 'group' => $group],
        );

        Cache::forget("setting.{$key}");

        return $setting;
    }
}
