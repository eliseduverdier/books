<?php

use App\BookApp;

require dirname(__DIR__) . '/books/app/vendor/autoload.php';
require dirname(__DIR__) . '/books/app/src/BookApp.php';

if ($_POST) {
    (new BookApp())->delete($_POST);
}
