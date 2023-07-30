<?php

namespace App\Tests\Util;

class Assert
{
    private static int $PLAYED_COUNT = 0;
    private static int $FAILED_COUNT = 0;
    private static int $PASSED_COUNT = 0;

    public static function assert(bool $condition, string $message = ''): void
    {
        if ($condition) {
            self::success();
        } else {
            $backtrace = debug_backtrace();
            $file = explode('/', $backtrace[1]['file']);
            $from = ' (' . array_pop($file) . ':' . $backtrace[1]['line'] . ')';
            self::error($message, $from);
        }
    }

    // Type assertions

    public static function isNumeric(mixed $value, string $message = null): void
    {
        self::assert(is_numeric($value), $message ?? "Expected a number, got $value");
    }

    public static function isString(mixed $value, string $message = null): void
    {
        self::assert(is_string($value), $message ?? 'Expected a string');
    }

    public static function isArray(mixed $value, string $message = null): void
    {
        self::assert(is_array($value), $message ?? 'Expected an array');
    }

    public static function true(mixed $value, string $message = null): void
    {
        self::assert($value === true, $message ?? 'Expected true');
    }

    public static function false(mixed $value, string $message = null): void
    {
        self::assert($value === false, $message ?? 'Expected false');
    }

    public static function isNull(mixed $value, string $message = null): void
    {
        self::assert($value === null, $message ?? 'Expected value to be null');
    }

    /// Comparisons

    public static function exists(mixed $value, string $message = null): void
    {
        self::assert($value !== null, 'Excepting value to exist, got null');
    }

    public static function equals(mixed $value1, mixed $value2, string $message = null): void
    {
        if (is_string($value1) && is_string($value2)) {
            $value1 = trim($value1);
            $value2 = trim($value2);
        }
        self::assert($value1 === $value2,
                $message
                ??
                is_scalar($value1) && is_scalar($value2) ? "Expected $value1 to equals $value2"
                : 'Expected values to be equal');
    }

    public function lesserThan(mixed $value1, mixed $value2, string $message = null): void
    {
        self::assert($value1 < $value2, $message ?? 'Expected ' . print_r($value1, true) . ' to be greater than ' . print_r($value2, true));
    }

    public function greatherThan(mixed $value1, mixed $value2, string $message = null): void
    {
        self::assert($value1 > $value2, $message ?? 'Expected ' . print_r($value1, true) . ' to be greater than ' . print_r($value2, true));
    }

    public static function hasKey(array $array, mixed $value, string $message = null): void
    {
        self::assert(array_key_exists($value, $array), $message ?? "Expected array to have key $value");
    }

    /// Etc
    public static function count(int $expected, mixed $value, string $message = null): void
    {
        $count = count($value);
        self::assert(is_iterable($value) && $count == $expected, $message ?? "Expected array to have $expected elements, found $count instead");
    }


    public static function contains(string $needle, string $haystack, string $message = null): void
    {
        self::assert(str_contains($haystack, $needle), $message ?? "Expected to find $needle");
    }

    public static function doesNotContains(string $needle, string $haystack, string $message = null): void
    {
        self::assert(!str_contains($haystack, $needle), $message ?? "Did not expect to find $needle");
    }

    // Exceptions

    public static function throwsException(mixed $value, string $message = null): void
    {
        try {
            self::error($message ?? 'Didn’t throw exception');
        } catch (\Throwable $e) {
            self::success();
        }
    }

    // Utils

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
        if (self::$FAILED_COUNT === 0)
        echo '🎉'.PHP_EOL;
    }

    protected static function success(): void
    {
        self::$PLAYED_COUNT++;
        self::$PASSED_COUNT++;
        echo '✓';
    }

    protected static function error(string $message, string $from = ''): void
    {
        self::$PLAYED_COUNT++;
        self::$FAILED_COUNT++;
        echo PHP_EOL . '✗ ' . $message . $from . PHP_EOL;
    }
}
