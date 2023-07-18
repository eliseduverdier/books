<?php

namespace App;

use App\Data\FileManager;
use App\Data\Database;

class BooksData
{
    protected Database $dataManager;
    protected array $notes;
    protected array $types;

    public function __construct()
    {
        $this->dataManager = new Database();
        $this->notes = $this->dataManager->getNotes();
        $this->types = $this->dataManager->getTypes();
    }

    public function getBooks(array $filter, array $sort): array
    {
        return $this->dataManager->getAll($filter, $sort);
    }

    public function getNotes(): array
    {
        return $this->dataManager->getNotes();
    }

    public function getTypes(): array
    {
        return $this->dataManager->getTypes();
    }

    public function getBook(string $slug): array
    {
        return $this->dataManager->getOne($slug);
    }

    public function save(array $newBook): bool
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
