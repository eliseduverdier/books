<?php

/**
 * Global methods for debug, and other dev needs
 */

function dump(...$var): void
{
    $backtrace = debug_backtrace();
    $from = 'FROM ' . $backtrace[0]['file'] . ':' . $backtrace[0]['line'];
    e($from, ...$var);
}

function dd(...$var): void
{
    $backtrace = debug_backtrace();
    $from = 'FROM ' . $backtrace[0]['file'] . ':' . $backtrace[0]['line'];
    e($from, ...$var);
    die;
}

function e(string $from, mixed ...$values): void
{
    // console env
    if (str_contains($from, 'test')) {
        echo "$from: \n";
        foreach ($values as $value) {
            var_dump($value ?? 'null');
        }
        return;
    }

    // web env
    echo "<pre style='margin-bottom: 0;'>$from</pre>";
    foreach ($values as $value) {
        echo '<pre style="background:black; color: white; padding: 2px;margin-top: 0;">';
        print_r($value ?? '<em>null</em>');
        echo '</pre>';
    }
}
