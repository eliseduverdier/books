<?php

namespace App\Tests\Util;

class Assert
{
    private static int $PLAYED_COUNT = 0;
    private static int $FAILED_COUNT = 0;
    private static int $PASSED_COUNT = 0;

    public static function throwsException(mixed $value, string $message = null): void
    {
        try {
            self::error($message ?? 'Didn’t throw exception');
        } catch (\Throwable $e) {
            self::success();
        }
    }

    public static function true(mixed $value, string $message = null): void
    {
        if ($value !== true) {
            self::error($message ?? 'Expected true');
        } else {
            self::success();
        }
    }

    public static function false(mixed $value, string $message = null): void
    {
        if ($value !== false) {
            self::error($message ?? 'Expected false');
        } else {
            self::success();
        }
    }

    /// Types

    public static function isInteger(mixed $value, string $message = null): void
    {
        if (!is_numeric($value)) {
            self::error($message ?? 'Expected a int');
        } else {
            self::success();
        }
    }

    public static function isString(mixed $value, string $message = null): void
    {
        if (!is_string($value)) {
            self::error($message ?? 'Expected a string');
        } else {
            self::success();
        }
    }

    public static function isArray(mixed $value, string $message = null): void
    {
        if (!is_array($value)) {
            self::error($message ?? 'Expected an array');
        } else {
            self::success();
        }
    }

    public static function isNull(mixed $value, string $message = null): void
    {
        if ($value !== null) {
            self::error($message ?? 'Expected nulll');
        } else {
            self::success();
        }
    }

    /// Comparisons

    public static function isEqual(mixed $value1, mixed $value2, string $message = null): void
    {
        if ($value1 !== $value2) {
            self::error($message ?? 'Expected values to be equal');
        } else {
            self::success();
        }
    }

    public static function isSame(mixed $value1, mixed $value2, string $message = null): void
    {
        if (json_encode($value1) != json_encode($value2)) {
            self::error($message ?? 'Expected values to be identical');
        } else {
            self::success();
        }
    }

    public static function hasKey(array $array, mixed $value, string $message = null): void
    {
        if (!array_key_exists($value, $array)) {
            self::error($message ?? "Expected array to have key $value");
        } else {
            self::success();
        }
    }

    public static function contains(string $value, string $subValue, string $message = null): void
    {
        if (str_contains($value, $subValue)) {
            self::success();
        } else {
            self::error($message ?? "Expected to find $subValue");
        }
    }

    public static function start(): void
    {
    }

    public static function end(): void
    {
        echo PHP_EOL;
        echo PHP_EOL . self::$PLAYED_COUNT . ' tests played';
        echo PHP_EOL . self::$PASSED_COUNT . ' tests passed';
        echo PHP_EOL . self::$FAILED_COUNT . ' tests failed';
        echo PHP_EOL;
    }

    protected static function success(): void
    {
        self::$PLAYED_COUNT++;
        self::$PASSED_COUNT++;
        echo '✓';
    }

    protected static function error(string $message): void
    {
        self::$PLAYED_COUNT++;
        self::$FAILED_COUNT++;
        echo PHP_EOL . '✗ ' . $message . PHP_EOL;
    }
}
