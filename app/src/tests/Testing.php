<?php

namespace App\Tests;

use App\Tests\Util\Assert;
use App\Tests\Util\FunctionalTestCase;

class Testing
{
    protected array $testClasses = [
        BookAppTest::class,
    ];

    public function run(): void
    {
        Assert::start();
        $this->runAllTests();
        Assert::end();
    }

    protected function runAllTests(): void
    {
        foreach ($this->testClasses as $className) {
            $methods = get_class_methods($className);

            $class = (new $className());
            $class->setUp();

            foreach ($methods as $method) {
                if (!str_starts_with($method, 'test')) continue;
                echo "Running $className::$method\n";
                $class->$method();
            }
            $class->tearDown();
        }
    }
}
