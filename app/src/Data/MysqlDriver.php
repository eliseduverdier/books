<?php

namespace App\Data;

use App\Util;

/**
 * TODO: refactor this class by CRUD + raw
 */
class MysqlDriver
{
    protected int|\mysqli $connection = 0;
    private const WHERE_MAPPING = [
        'author' => 'a.slug',
        'note' => 'note_id',
        'type' => 'type_id',
    ];
    private const SORT_MAPPING = [
        'author' => 'LOWER(author)',
        'note' => 'note_id',
    ];
    private const SORT_MAPPING_ORDER = [
        'author' => 'ASC',
        'note' => 'DESC',
    ];

    public function __construct(protected string $tableName = '')
    {
        // TODO find a cleaner way to handle this
        if (empty($_SERVER['SERVER_NAME']) || $_SERVER['SERVER_NAME'] === 'localhost') {
            require __DIR__ . '/../../.env.dist.php';
        } else {
            require __DIR__ . '/../../.env.php';
        }
        if (getenv('ENV') === 'test') {
            $DB_NAME = 'books_test';
        }

        $this->connection = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

        if (!$this->connection) {
            throw new \Exception('[DATABASE] Connection failed: ' . mysqli_connect_error());
        }
        mysqli_set_charset($this->connection, 'utf8');
    }

    public function selectAllBooks(string $fields = '*', array $filter = [], array $sort = []): array
    {
        $query = "
SELECT books.slug, title, finished_at, abandonned_at, type_id AS type,
       a.name AS author_name, author,
       n.note AS note, n.id AS note_id, n.legend as note_legend
FROM {$this->tableName} 
    LEFT JOIN books_notes n ON n.id = books.note_id
    LEFT JOIN books_author a ON a.slug = books.author 
    WHERE deleted_at IS NULL"
            . (empty($filter) ? '' : ' AND ' . $this->buildWhereClause($filter))
    . "\nORDER BY\n"
            . (empty($sort) ? 'abandonned_at ASC, finished_at IS NULL DESC, finished_at DESC' : $this->buildSortClause($sort))
            .';';

        $stmt = $this->connection->prepare($query);
        $this->bindWhereClause($filter, $stmt);
        $stmt->execute();

        $result = $stmt->get_result();
        return ($result->fetch_all(MYSQLI_ASSOC)) ?? [];
    }

    public function selectOne(array $by): array
    {
        $field = array_keys($by)[0];
        $stmt = $this->connection->prepare(
            "
SELECT books.slug, title, summary, author, a.name AS author_name, type_id AS type, n.note AS note, n.id AS note_id, finished_at, abandonned_at
FROM books
    LEFT JOIN books_notes n ON n.id = books.note_id 
    LEFT JOIN books_author a ON a.slug = books.author
WHERE $field = ?;
");
        $stmt->bind_param('s', array_values($by)[0]);
        $stmt->execute();

        $result = $stmt->get_result();
        return ($result->fetch_all(MYSQLI_ASSOC))[0] ?? [];
    }

    public function saveBook(array $data): bool
    {
        $this->saveAuthorIfNew($data);

        return $this->save($data);
    }

    public function save(array $data, string $tableName = null): bool
    {
        $tableName ??= $this->tableName;

        $keys = implode(', ', array_keys($data));
        $values = array_values($data);
        $valuesPlaceholders = [];
        foreach ($data as $v) {
            $valuesPlaceholders[] = '?';
        }
        $valuesPlaceholders = implode(', ', $valuesPlaceholders);
        $stmt = $this->connection->prepare("INSERT INTO $tableName ($keys) VALUES ($valuesPlaceholders);");

        $types = '';
        foreach ($data as $value) {
            $types .= $this->getValueTypeAsString($value);
        }
        $stmt->bind_param($types, ...$values);

        try {
            return $stmt->execute();
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return false;
            } else {
                throw $e;
            }
        }
    }

    public function edit(string $slug, array $data): bool
    {
        $assignations = [];
        foreach ($data as $k => $v) {
            $assignations[] = "$k = ?";
        }
        // todo edit author
        $assignations = implode(', ', $assignations);
        $stmt = $this->connection->prepare("UPDATE {$this->tableName} SET $assignations WHERE slug = ?;");
        $params = array_merge($data, [$slug]);

        $types = '';
        foreach ($params as $value) {
            $types .= $this->getValueTypeAsString($value);
        }
        $stmt->bind_param($types, ...array_values($params));
        return $stmt->execute();
    }

    public function delete(string $slug): bool
    {
        $stmt = $this->connection->prepare("UPDATE {$this->tableName} SET deleted_at = CURRENT_DATE WHERE slug = ?;");
        $stmt->bind_param('s', $slug);
        return $stmt->execute();
    }


    public function selectFromTable(string $tableName, string $fields = '*', ?string $by = null): array
    {
        $result = mysqli_query($this->connection, "SELECT $fields FROM $tableName" . ($by ? " ORDER BY $by" : '') . ";");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function rawQuery(string $query): void
    {
        mysqli_query($this->connection, $query);
    }

    protected function getValueTypeAsString(mixed $value): string
    {
        return match (gettype($value)) {
            'integer', 'boolean' => 'i',
            'double' => 'd',
            'array', 'object', 'resource' => 'b',
            /*'string', */ default => 's',
        };
    }

    protected function bindedQuery(string $query, array $bindedItems): \mysqli_result
    {

        $stmt = $this->connection->prepare($query);
        foreach ($bindedItems as $item) {
            $stmt->bind_param($this->getValueTypeAsString($item), $item);
        }
        $stmt->execute();
        return $stmt->get_result();
    }

    protected function buildWhereClause(array $filter): string
    {
        $whereClause = [];
        foreach ($filter as $k => $v) {
            $field = self::WHERE_MAPPING[$k] ?? $k;
            $whereClause[] = "$field = ?";
        }
        return implode("\n AND ", $whereClause);
    }

    protected function bindWhereClause(array $filter, \mysqli_stmt $stmt): void
    {
        foreach ($filter as $v) {
            $stmt->bind_param('s', $v);
        }
    }

    protected function saveAuthorIfNew(array $data): bool
    {
        $authorResult = $this->bindedQuery("SELECT slug FROM books_author WHERE slug = ?", [$data['author']]);
        if (empty($authorResult->fetch_all())) {
            $this->save(['slug' => Util::slugify($data['author']), 'name' => $data['author']], 'books_author');
            return true;
        }

        return false;
    }

    // TODO you know
    private function buildSortClause(array $sort): string
    {
        $sortClause = [];
        foreach ($sort as $k => $v) {
            $field = self::SORT_MAPPING[$v];
            $sortClause[] = "$field " . self::SORT_MAPPING_ORDER[$v];
        }
        return implode(', ', $sortClause);

    }
}
