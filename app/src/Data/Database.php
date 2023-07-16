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

    public function getAll(array $filter, array $sort): array
    {
        return $this->driver->selectAll(filter: $filter, sort: $sort);
    }

    public function getOne(string $slug): array
    {
        return $this->driver->selectOne(['books.slug' => $slug]);
    }

    public function save(array $data): bool
    {
        return $this->driver->saveBook([
            'books.slug' => Util::slugifyBook($data),
            'title' => $data['title'],
            'author' => $data['author'],
            'type_id' => $data['type'] === '' ? null : $data['type'],
            'note_id' => $data['note'] === '' ? null : $data['note'],
            'finished_at' => empty($data['finished_at']) ? null : $data['finished_at'],
        ]);
    }

    public function edit(string $slug, array $data): bool
    {
        return $this->driver->edit($slug, [
            'title' => $data['title'],
            'author' => $data['author'],
            'type_id' => $data['type'] === '' ? null : $data['type'],
            'note_id' => $data['note'] === '' ? null : $data['note'],
            'summary' => $data['summary'],
            'finished_at' => empty($data['finished_at']) ? null : $data['finished_at'],
            'abandonned_at' => $data['abandonned_at'] ? date('Y-m-d') : null,
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
