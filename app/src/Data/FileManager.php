<?php

namespace App\Data;

class FileManager implements DataInterface
{
    protected string $filePath;

    public function __construct()
    {
        $this->filePath = __DIR__ . '/../data/books.json';
    }

    public function getAll(): array
    {
        $json = json_decode(file_get_contents($this->filePath), true) ?? [];
        uasort($json, fn ($a, $b) => ($a['finished_at'] > $b['finished_at']) ? -1 : 1);

        return $json;
    }

    public function getOne(string $slug): array
    {
        return json_decode(file_get_contents($this->filePath), true)[$slug] ?? [];
    }

    public function save(array $data): bool
    {
        $fileContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $fp = fopen($this->filePath, 'w');

        return (bool) fwrite($fp, $fileContent);
    }
}
