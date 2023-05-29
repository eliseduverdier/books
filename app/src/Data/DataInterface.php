<?php

namespace App\Data;

interface DataInterface
{
    public function getAll(): array;

    public function getOne(string $slug): array;
}
