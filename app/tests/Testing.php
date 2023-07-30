<?php

namespace App\Tests;

use App\Tests\Util\Assert;
use App\Tests\Util\FunctionalTestCase;

class Testing
{
    protected array $testClasses = [
        BookAppTest::class,
    ];

    public function run(array $args): void
    {
        Assert::start();
        $this->runAllTests(count($args) > 1 ? $args[1] : null);
        Assert::end();
    }

    protected function runAllTests(?string $methodMatch): void
    {
        foreach ($this->testClasses as $className) {
            $methods = get_class_methods($className);

            $class = (new $className());
            $class->setUp();

            foreach ($methods as $method) {
                if (!str_starts_with($method, 'test')) continue;
                if ($methodMatch && !str_contains($method, $methodMatch)) continue;
                $class->$method();
            }
            $class->tearDown();
        }
    }
}
