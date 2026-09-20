<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function valueFor(string $key, mixed $default = null): mixed
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    public static function setValue(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
    }

    public static function decimal(string $key, float $default): float
    {
        return (float) static::valueFor($key, $default);
    }

    public static function integer(string $key, int $default): int
    {
        return (int) static::valueFor($key, $default);
    }

    public static function boolean(string $key, bool $default = false): bool
    {
        return filter_var(static::valueFor($key, $default ? '1' : '0'), FILTER_VALIDATE_BOOLEAN);
    }
}
