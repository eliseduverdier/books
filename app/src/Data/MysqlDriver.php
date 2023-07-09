<?php

namespace App\Data;

use App\Util;

class MysqlDriver
{
    protected int|\mysqli $connection = 0;
    private const WHERE_MAPPING = [
        'author' => 'a.slug',
        'note' => 'note_id',
        'type' => 'type_id',
    ];

    public function __construct(protected string $tableName = '')
    {
        // TODO find a better way to handle this
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

    public function selectAll(string $fields = '*', array $filter = []): array
    {
        $stmt = $this->connection->prepare("
SELECT books.slug, title, a.name AS author_name, author, type_id AS type, n.note AS note, n.id AS note_id, finished_at
FROM {$this->tableName} 
    LEFT JOIN books_notes n ON n.id = books.note_id
    LEFT JOIN books_author a ON a.slug = books.author " .
            (empty($filter) ? '' : $this->buildWhereClause($filter)) .
            ' ORDER BY finished_at IS NULL DESC, finished_at DESC;'
        );
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
SELECT books.slug, title, summary, author, a.name AS author_name, type_id AS type, n.note AS note, n.id AS note_id, finished_at 
FROM books
    LEFT JOIN books_notes n ON n.id = books.note_id 
    LEFT JOIN books_author a ON a.slug = books.author
WHERE $field = ?;
"
        );
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

        return $stmt->execute();
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
        $stmt = $this->connection->prepare("DELETE FROM {$this->tableName} WHERE slug = ?;");
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
        return "\nWHERE " . implode(' AND ', $whereClause);
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
}
