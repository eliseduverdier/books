<?php

use App\BookApp;

require dirname(__DIR__) . '/books/app/vendor/autoload.php';
require dirname(__DIR__) . '/books/app/src/BookApp.php';

try {
    if ($_POST) {
        (new BookApp())->saveNew($_POST);
    }

    (new BookApp())->list();

} catch (Exception $e) {
    var_dump($e);die;
}
