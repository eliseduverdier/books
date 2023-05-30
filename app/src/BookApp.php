<?php

namespace App;

use App\User\Authentication;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class BookApp
{
    protected Environment $twig;
    private BooksData $booksData;
    private bool $authenticated;

    public function __construct()
    {
        $this->twig = (new Environment(
            new FilesystemLoader(__DIR__ . '/../front/templates/'),
        ));
        $this->booksData = new \App\BooksData();
        $this->authenticated = (new Authentication())->isAuthenticated();
    }

    public function list(): void
    {
        $this->twig->display(
            'list.html.twig',
            [
                'books' => $this->booksData->getBooks(),
                'types' => BooksData::TYPES,
                'notes' => BooksData::NOTES,
                'authenticated' => $this->authenticated,
            ]
        );
    }

    public function show(string $slug): void
    {
        $this->filterUnidentifiedUser();

        $this->twig->display(
            'edit.html.twig',
            [
                'book' => $this->booksData->getBook($slug),
                'types' => BooksData::TYPES,
                'notes' => BooksData::NOTES,
            ]
        );
    }

    public function saveNew(array $data): void
    {
        $this->filterUnidentifiedUser();

        $this->booksData->save($data);
    }

    public function edit(array $data, string $slug): void
    {
        $this->filterUnidentifiedUser();
        try {
            $this->booksData->edit($slug, $data);
            header('HTTP/2 301 Moved Permanently');
            header('Location: ..');
        } catch (\Exception $e) {
            $this->twig->display(
                'error.html.twig',
                ['error' => $e]
            );
        }
    }

    public function delete(string $slug): void
    {
        $this->filterUnidentifiedUser();
        try {
            $this->booksData->delete($slug);
            header('HTTP/2 301 Moved Permanently');
            header('Location: ..');
        } catch (\Exception $e) {
            $this->twig->display(
                'error.html.twig',
                ['error' => $e]
            );
        }
    }

    protected function filterUnidentifiedUser(): void
    {
        if (!$this->authenticated) {
            $this->twig->display('user/not-logged-in.html.twig');
            exit;
        }
    }
}
