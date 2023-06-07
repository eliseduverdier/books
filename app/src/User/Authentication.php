<?php

namespace App\User;

class Authentication
{
    private string $username;
    private string $password;

    public function __construct()
    {
        $_SERVER['SERVER_NAME'] === 'localhost'
            ? require __DIR__ . '/../../.env.dist.php'
            : require __DIR__ . '/../../.env.php';
        $this->username = $USERNAME;
        $this->password = $PASSWORD;
    }

    public function login(string $username, string $password): bool
    {
        if ($username === $this->username && $password === $this->password) {
            session_start();
            setcookie('user', md5($username . $password));
            header("Location: index.php");
            return true;
        }
        return false;
    }

    public function isAuthenticated(): bool
    {
        return $_COOKIE['user'] === md5($this->username . $this->password);
    }

}
