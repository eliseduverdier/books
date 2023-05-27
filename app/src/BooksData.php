<?php

namespace App;

use App\Data\FileManager;

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

    protected FileManager $fileManager;
    protected string $filePath;
    protected array $booksData;

    public function __construct()
    {
        $this->fileManager = new FileManager();
        $this->filePath = __DIR__ . '/../data/books.json';
        $this->booksData = $this->fileManager->readFile($this->filePath);
    }

    public function getData(): array
    {
        return [
            'books' => $this->parseBooks(),
            'types' => self::TYPES,
            'notes' => self::NOTES,
        ];
    }

    public function save(array $book): void
    {
        $newData = array_merge($this->booksData, [
            Util::slugify($book) => [
            'title' => $book['title'],
            'author' => $book['author'],
            'type' => $book['type'],
            'note' => $book['note'],
            'date' => $book['date'],
        ]]);

        $this->fileManager->saveFile($this->filePath, $newData);
    }

    public function edit(array $newBook, string $slug): array
    {
        if (array_key_exists($slug, $this->booksData)) {
            unset($this->booksData[$slug]);
        } else {
            throw new \Exception('Book not found');
        }

        $newData = array_merge($this->booksData, [
            Util::slugify($newBook) => [
            'title' => $newBook['title'],
            'author' => $newBook['author'],
            'type' => $newBook['type'],
            'note' => $newBook['note'],
            'date' => $newBook['date'],
        ]]);

        $this->fileManager->saveFile($this->filePath, $newData);

        return $newData;
    }

    public function delete(array $book): void
    {
        unset($this->booksData[Util::slugify($book)]);

        $this->fileManager->saveFile($this->filePath, $this->booksData);
    }

    protected function parseBooks(): array
    {
        $json = $this->fileManager->readFile($this->filePath);
        uasort($json, fn ($a, $b) => ($a['date'] > $b['date']) ? -1 : 1);

        return $json;
    }
}
