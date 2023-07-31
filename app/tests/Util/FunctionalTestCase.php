<?php

namespace App\Tests\Util;

use App\Data\MysqlDriver;

class FunctionalTestCase
{
    private \mysqli $mysql;

    public const DB_HOST = "localhost";
    public const DB_USER = "elise";
    public const DB_PASSWORD = "";
    // todo create database books_test
    public const DB_NAME = "books";

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
        // TODO: 'CREATE DATABASE books_test;'

        $structure = explode('--', file_get_contents(__DIR__ . '/../../data/data-structure.sql'));
        foreach ($structure as $query) {
            mysqli_query($this->mysql, $query);
        }
        $data = explode('--', file_get_contents(__DIR__ . '/../../data/data-tests.sql'));
        foreach ($data as $query) {
            mysqli_query($this->mysql, $query);
        }
    }

    public function crawl(string $url, string $method = 'GET', array $postOptions = [], bool $authenticated = false): string
    {
        $req = curl_init();
        curl_setopt($req, CURLOPT_URL, ($_SERVER['HTTP_HOST'] ?? 'localhost:1111') . $url);
        curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($req, CURLOPT_FOLLOWLOCATION, 1);

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
