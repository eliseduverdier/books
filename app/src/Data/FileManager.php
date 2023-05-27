<?php

namespace App\Data;

class FileManager
{
    public function readFile(string $filePath): array
    {
        return json_decode(file_get_contents($filePath), true) ?? [];
    }

    public function saveFile(string $filePath, array $data): bool
    {
        $fileContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $fp = fopen($filePath, 'w');

        return (bool) fwrite($fp, $fileContent);
    }
}
