<?php

require dirname(__DIR__) . '/books/app/vendor/autoload.php';
require dirname(__DIR__) . '/books/app/src/Controller.php';

if ($_POST) {
    (new \App\BookApp())->saveNew($_POST);
}

(new \App\BookApp())->display();
