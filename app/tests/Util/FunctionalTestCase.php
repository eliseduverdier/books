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
        $this->mysql = mysqli_connect(self::DB_HOST, self::DB_USER, self::DB_PASSWORD);
    }

    public function setUp(): void
    {
        $this->setUpDatabase();
    }

    public function setUpDatabase(bool $populate = true): void
    {
        mysqli_query($this->mysql, sprintf('CREATE DATABASE IF NOT EXISTS %s', self::DB_NAME));
        mysqli_query($this->mysql, sprintf('USE %s;', self::DB_NAME));

        $structure = explode('--', file_get_contents(__DIR__ . '/../../data/data-structure.sql'));
        foreach ($structure as $query) {
            mysqli_query($this->mysql, $query);
        }
        if ($populate) {
            $data = explode('--', file_get_contents(__DIR__ . '/../../data/data-tests.sql'));
            foreach ($data as $query) {
                mysqli_query($this->mysql, $query);
            }
        }
    }

    public function crawl(string $url, string $method = 'GET', array $postOptions = [], bool $authenticated = false): string
    {
        $req = curl_init();
        curl_setopt($req, CURLOPT_URL, ($_SERVER['HTTP_HOST'] ?? 'localhost:1111') . $url);
        curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($req, CURLOPT_FOLLOWLOCATION, 1);

        // Set headers for test
        curl_setopt($req, CURLOPT_HTTPHEADER, [
            'ENV: test',
        ]);


        // Set options
        if ($authenticated) {
            curl_setopt($req, CURLOPT_COOKIE, "user=" . md5('adminpw'));
        }

        if ($method === 'POST') {
            curl_setopt($req, CURLOPT_POST, 1);
        }

        if ($postOptions) {
            curl_setopt($req, CURLOPT_POSTFIELDS, $postOptions);
        }

        // Make the request
        $output = curl_exec($req);
        curl_close($req);

        return preg_replace('~[\n\r\s]+~', ' ', $output);
    }

    public function tearDown(): void
    {
//        mysqli_query($this->mysql, "DROP DATABASE IF EXISTS `books_test`;");
    }
}
