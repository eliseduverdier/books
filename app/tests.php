<?php

use App\Tests\Util\Testing;

require dirname(__DIR__) . '/app/vendor/autoload.php';
require dirname(__DIR__) . '/app/src/BookApp.php';
require dirname(__DIR__) . '/app/src/Debug/util.php';

(new Testing())->run($argv);
