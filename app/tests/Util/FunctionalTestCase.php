<?php

namespace App\Tests\Util;

use App\Data\MysqlDriver;

class FunctionalTestCase
{
    private \mysqli $mysql;

    public const DB_HOST = "localhost";
    public const DB_USER = "elise";
    public const DB_PASSWORD = "";
    public const DB_NAME = "books_tests";

    public function __construct()
    {
        $this->mysql = mysqli_connect(self::DB_HOST, self::DB_USER, self::DB_PASSWORD, self::DB_NAME);
    }

    public function setUp(): void
    {
        $this->setUpDatabase();
    }

    public function setUpDatabase(): void
    {
        echo " >>>> Setting up database...\n";
        // create database, tables, and insert sample data
        mysqli_query($this->mysql, "DROP TABLE IF EXISTS `books_test`;");
        mysqli_query($this->mysql, file_get_contents(__DIR__ . '/../../data/data-structure.sql'));
        mysqli_query($this->mysql, file_get_contents(__DIR__ . '/../../data/data-tests.sql'));
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
        // delete tables
        mysqli_query($this->mysql, "DROP TABLE IF EXISTS `books_test` ;");
    }
}
