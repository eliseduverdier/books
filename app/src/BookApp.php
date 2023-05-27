<?php

namespace App;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class BookApp
{
    protected Environment $twig;

    public function __construct()
    {
        $this->twig = (new Environment(
            new FilesystemLoader(__DIR__ . '/../front/templates/'),
        ));
    }

    public function display(): void
    {
        $this->twig->display(
            'list.html.twig',
            (new BooksData())->getData()
        );
    }

    public function displayEdit(): void
    {
        $booksData = new BooksData();

        $this->twig->display(
            'edit.html.twig',
            [
                'book' => $booksData->getData()['books'][$_GET['s']],
                'types' => $booksData->getData()['types'],
                'notes' => $booksData->getData()['notes'],
            ]
        );
    }

    public function saveNew(array $data): void
    {
        (new BooksData())->save($data);
    }

    public function edit(array $data, string $slug): void
    {
        new BooksData();
        try {
            (new BooksData())->edit($data, $slug);
            header('Location: ../index.php');
        } catch (\Exception $e) {
            $this->twig->display(
                'error.html.twig',
                ['error' => $e->getMessage()]
            );
        }
    }

    public function delete(array $data): void
    {
        try {
            (new BooksData())->delete($data);
        } catch (\Exception $e) {
            $this->twig->display(
                'error.html.twig',
                ['error' => $e->getMessage()]
            );
        }
    }
}
