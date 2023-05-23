<?php

namespace App;

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

    public function save(array $book)
    {
        $newData = array_merge($this->booksData, [[
            'title' => $book['title'],
            'author' => $book['author'],
            'type' => $book['type'],
            'note' => $book['note'],
            'date' => $book['date'],
        ]]);

        return $this->fileManager->saveFileAsJson($this->filePath, $newData);
    }

    protected function parseBooks(): array
    {
        $json = $this->fileManager->readFile($this->filePath);

        usort($json, fn ($a, $b) => ($a['date'] > $b['date']) ? -1 : 1);

        return $json;
    }
}
