<?php

namespace RCV\Core\Services\Config;

use Illuminate\Support\Facades\Config as LaravelConfig;
use InvalidArgumentException;

class ConfigManager
{
    public function get(string $key, mixed $default = null): mixed
    {
        return LaravelConfig::get($key, $default);
    }

    public function set(string $key, mixed $value): void
    {
        LaravelConfig::set($key, $value);
    }

    public function validate(array $schema, array $data): void
    {
        foreach ($schema as $field => $type) {
            if (!array_key_exists($field, $data)) {
                throw new InvalidArgumentException("Missing config field: {$field}");
            }
            if ($type !== null && gettype($data[$field]) !== $type) {
                throw new InvalidArgumentException("Invalid type for {$field}, expected {$type}");
            }
        }
    }
}


