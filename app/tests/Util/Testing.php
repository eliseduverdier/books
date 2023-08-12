<?php

namespace App\Tests\Util;

use App\Tests\BookAppEditionTest;
use App\Tests\BookAppViewTest;

class Testing
{
    protected array $testClasses = [
        BookAppViewTest::class,
        BookAppEditionTest::class,
    ];

    public function run(array $args): void
    {
        Assert::start();
        $this->runAllTests(count($args) > 1 ? $args[1] : null);
        Assert::end();
    }

    protected function runAllTests(?string $methodMatch): void
    {
        try {

        foreach ($this->testClasses as $className) {
            echo "\n\n\033[1m\033[32m >>> Testing $className\033[0m";
            $methods = get_class_methods($className);

            $class = (new $className());
            $class->setUp();

            foreach ($methods as $method) {
                if (!str_starts_with($method, 'test')) continue;
                if ($methodMatch && !str_contains($method, $methodMatch)) continue;
                echo "\n $method ";
                $class->$method();
            }
            $class->tearDown();
        }
    } catch (\Exception $e) {
}
    }
}
