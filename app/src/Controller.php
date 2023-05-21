<?php

namespace App;

class BookApp
{
    public function display()
    {
        $twig = (new \Twig\Environment(
            new \Twig\Loader\FilesystemLoader(__DIR__ . '/../front/templates/'),
        ));

        $twig->display(
            'list.html.twig',
            (new BooksData())->getData()
        );
    }

    public function saveNew(array $data): bool
    {
        return (new BooksData())->save($data);
    }
}
