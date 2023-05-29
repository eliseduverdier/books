<?php

namespace App;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class BookApp
{
    protected Environment $twig;
    private BooksData $booksData;

    public function __construct()
    {
        $this->twig = (new Environment(
            new FilesystemLoader(__DIR__ . '/../front/templates/'),
        ));
        $this->booksData = new \App\BooksData();
    }

    public function list(): void
    {
        $this->twig->display(
            'list.html.twig',
            [
                'books' => $this->booksData->getBooks(),
                'types' => BooksData::TYPES,
                'notes' => BooksData::NOTES,
            ]
        );
    }

    public function displayEdit(): void
    {
        $this->twig->display(
            'edit.html.twig',
            [
                'slug' => $_GET['s'],
                'book' => $this->booksData->getBook($_GET['s']),
                'types' => BooksData::TYPES,
                'notes' => BooksData::NOTES,
            ]
        );
    }

    public function saveNew(array $data): void
    {
        $this->booksData->save($data);
    }

    public function edit(array $data, string $slug): void
    {
        try {
            $this->booksData->edit($slug, $data);
            header("HTTP/2 301 Moved Permanently");
            header('Location: index.php');
        } catch (\Exception $e) {
            $this->twig->display(
                'error.html.twig',
                ['error' => $e]
            );
        }
    }

    public function delete(string $slug): void
    {
        try {
            $this->booksData->delete($slug);
            header("HTTP/2 301 Moved Permanently");
            header('Location: index.php');
        } catch (\Exception $e) {
            $this->twig->display(
                'error.html.twig',
                ['error' => $e]
            );
        }
    }
}
