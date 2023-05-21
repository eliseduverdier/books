<?php

namespace App;

class BooksData
{
    protected string $filePath = __DIR__ . '/../data/books.json';

    public function getData(): array
    {
        return [
            'books' => $this->parseBooks(),
            'types' => $this->getTypes(),
            'notes' => $this->getNotes(),
        ];
    }

    public function save(array $book)
    {
        $newData = json_encode(array_merge($this->getFileData(), [[
            'title' => $book['title'],
            'author' => $book['author'],
            'type' => $book['type'],
            'note' => $book['note'],
            'date' => $book['date'],
        ]]));
        $fp = fopen($this->filePath, 'w');

        return fwrite($fp, $newData);
    }

    protected function parseBooks(): array
    {
        $json = $this->getFileData();

        usort($json, fn ($a, $b) => ($a['date'] > $b['date']) ? -1 : 1);

        return $json;
    }

    protected function getFileData()
    {
        return json_decode(file_get_contents($this->filePath), true) ?? [];
    }

    protected function getTypes(): array
    {
        return [
            'essay',
            'novel',
            'biography',
            'art',
            'BD',
        ];
    }

    protected function getNotes(): array
    {
        return [
            '6' => '🤯 (extraordinary)',
            '5' => '⭐ ⭐ ⭐ ⭐ (great)',
            '4' => '🥰 (adorable)',
            '3' => '⭐ ⭐ ⭐ (good)',
            '2' => '⭐ ⭐ (nice)',
            '1' => '⭐ (mh)',
            '0' => '😞',
        ];
    }
}
