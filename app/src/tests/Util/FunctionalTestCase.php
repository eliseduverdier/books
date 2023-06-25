<?php

namespace App\Tests\Util;

use App\Data\MysqlDriver;

class FunctionalTestCase
{
    private MysqlDriver $driver;

    public function setUp(): void
    {
        $this->setUpDatabase();
    }

    public function setUpDatabase(): void
    {
        echo " >>>> Setting up database...\n";
        // create database, tables, and insert sample data
        $this->driver = new MysqlDriver('books_test');
        $this->driver->rawQuery("CREATE DATABASE IF NOT EXISTS `books_test`;  ");
        $this->driver->rawQuery("USE `books_test`;");

        $this->driver->rawQuery(file_get_contents(__DIR__ . '/../../../data/data-structure.sql'));
    }

    public function crawl(string $url, string $method = 'GET', array $options = []): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, ($_SERVER['HTTP_HOST'] ?? '') . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    public function tearDown(): void
    {
        // delete database
        $this->driver->rawQuery('DROP TABLE IF EXISTS books_test');
    }
}
