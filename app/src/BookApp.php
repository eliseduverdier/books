<?php

namespace App;

use App\User\Authentication;
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
            [
                'debug' => true,
            ]
        ));
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        $this->booksData = new \App\BooksData();
    }

    public function list(bool $authenticated = false, array $filter = [], array $sort = []): void
    {
        $this->display(
            'list.html.twig',
            [
                'books' => $this->booksData->getBooks($filter, $sort),
                'filter' => $filter,
                'sort' => $sort,
                'active' => $_GET['current'] ?? '',
            ],
            $authenticated
        );
    }

    public function show(string $slug, bool $authenticated = false): void
    {
        $this->display(
            $authenticated ? 'edit.html.twig' : 'see.html.twig',
            ['book' => $this->booksData->getBook($slug)],
            $authenticated
        );
    }

    public function saveNew(array $data): void
    {
        $slug = $this->booksData->save($data);

        header('HTTP/2 301 Moved Permanently');
        header("Location: index.php?current=$slug#$slug");
    }

    public function edit(array $data, string $slug): void
    {
        try {
            $this->booksData->edit($slug, $data);
            if ($_POST['keep-editing']) {
                $this->show($slug, true);
                return;
            }
            header('HTTP/2 301 Moved Permanently');
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
            header('HTTP/2 301 Moved Permanently');
            header('Location: index.php');
        } catch (\Exception $e) {
            $this->twig->display(
                'error.html.twig',
                ['error' => $e]
            );
        }
    }

    public function loginForm(): void
    {
        $this->twig->display('user/login.html.twig');
    }

    public function login(string $username, string $password): void
    {
        (new Authentication())->login($username, $password);
        header('HTTP/2 301 Moved Permanently');
        header('Location: index.php');
    }

    public function logout(): void
    {
        (new Authentication())->logout();
        header('HTTP/2 301 Moved Permanently');
        header('Location: index.php');
    }

    protected function display(string $template, array $data, bool $authenticated): void
    {
        $this->twig->display(
            $template,
            array_merge(
                $data,
                [
                    'types' => $this->booksData->types,
                    'notes' => $this->booksData->notes,
                    'authors' => $this->booksData->authors,
                    'authenticated' => $authenticated,
                ]
            )
        );
    }
}
