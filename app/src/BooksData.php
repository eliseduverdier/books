<?php

namespace App;

use App\Data\FileManager;
use App\Data\Database;

class BooksData
{
    public const TYPES =
    [
        'essay',
        'novel',
        'biography',
        'art',
        'BD',
    ];

    public const NOTES = [
        '6' => '🤯 (extraordinary)',
        '5' => '⭐ ⭐ ⭐ ⭐ (great)',
        '4' => '🥰 (adorable)',
        '3' => '⭐ ⭐ ⭐ (good)',
        '2' => '⭐ ⭐ (nice)',
        '1' => '⭐ (mh)',
        '0' => '😞',
    ];

    protected Database $dataManager;
    protected array $booksData;
    protected array $notes;
    protected array $types;

    public function __construct()
    {
        $this->dataManager = new Database();
        $this->booksData = $this->dataManager->getAll();
        $this->notes = $this->dataManager->getNotes();
        $this->types = $this->dataManager->getTypes();
    }

    public function getBooks(): array
    {
        return $this->dataManager->getAll();
    }

    public function getBook(string $slug): array
    {
        return $this->dataManager->getOne($slug);
    }

    public function save(array $newBook): void
    {
        $this->dataManager->save($newBook);
    }

    public function edit(string $slug, array $newBook): array
    {
        $this->dataManager->edit($slug, $newBook);

        return $newBook;
    }

    public function delete(string $slug): void
    {
        $this->dataManager->delete($slug);
    }
}
