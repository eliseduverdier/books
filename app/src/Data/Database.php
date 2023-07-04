<?php

namespace App\Data;

use App\Util;

class Database implements DataInterface
{
    protected int|\mysqli $connection = 0;
    private MysqlDriver $driver;

    public function __construct()
    {
        $this->driver = new MysqlDriver('books');
    }

    public function getAll(): array
    {
        return $this->driver->selectAll();
    }

    public function getOne(string $slug): array
    {
//        dd($this->driver->selectOne(['slug' => $slug]));
        return $this->driver->selectOne(['slug' => $slug]);
    }

    public function save(array $data): bool
    {
        return $this->driver->save([
            'slug' => Util::slugify($data),
            'title' => $data['title'],
            'author' => $data['author'],
            'type_id' => $data['type'] ?? null,
            'note_id' => $data['note'] === '' ? null : $data['note'],
            'finished_at' => empty($data['date']) ? null : $data['date'],
        ]);
    }

    public function edit(string $slug, array $data): bool
    {
        return $this->driver->edit($slug, [
            'title' => $data['title'],
            'author' => $data['author'],
            'type_id' => $data['type'],
            'note_id' => $data['note'] === '' ? null : $data['note'],
            'summary' => $data['summary'],
            'finished_at' => empty($data['date']) ? null : $data['date'],
        ]);
    }

    public function delete(string $slug): bool
    {
        return $this->driver->delete($slug);
    }

    public function getNotes(): array
    {
        return $this->driver->selectFromTable('books_notes');
    }
    public function getTypes(): array
    {
        return $this->driver->selectFromTable('books_types');
    }
}
