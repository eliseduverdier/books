<?php

namespace App;

use App\Data\FileManager;
use App\Data\Database;

class BooksData
{
    protected Database $dataManager;
    public array $notes;
    public array $types;
    public array $authors;

    public function __construct()
    {
        $this->dataManager = new Database();
        $this->notes = $this->dataManager->getNotes();
        $this->types = $this->dataManager->getTypes();
        $this->authors = $this->dataManager->getAuthors();
    }

    public function getBooks(array $filter, array $sort): array
    {
        return $this->dataManager->getAll($filter, $sort);
    }

    public function getBook(string $slug): array
    {
        return $this->dataManager->getOne($slug);
    }

    public function save(array $newBook): string
    {
        return $this->dataManager->save($newBook);
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
