<?php

namespace App\Data;

interface DataInterface
{
    public function getAll(array $filter, array $sort): array;

    public function getOne(string $slug): array;
}
