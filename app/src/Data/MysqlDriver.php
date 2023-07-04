<?php

namespace App\Data;

class MysqlDriver
{
    protected int|\mysqli $connection = 0;

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

    public function selectAll(string $fields = '*'): array
    {
        $result = mysqli_query($this->connection, '
SELECT slug, title, author, type_id AS type, books_notes.note, finished_at
FROM books LEFT JOIN books_notes ON books_notes.id = books.note_id
ORDER BY finished_at IS NULL DESC, finished_at DESC;
         ');

        return mysqli_fetch_all($result, MYSQLI_ASSOC) ?? [];
    }

    public function selectOne(array $by): array
    {
        $field = array_keys($by)[0];
        $stmt = $this->connection->prepare(
            "
SELECT slug, title, summary, author, type_id AS type, books_notes.note, books_notes.id AS note_id, finished_at 
FROM books LEFT JOIN books_notes ON books_notes.id = books.note_id 
WHERE $field = ?;
"
        );
        $stmt->bind_param('s', array_values($by)[0]);
        $stmt->execute();

        $result = $stmt->get_result();
        return ($result->fetch_all(MYSQLI_ASSOC))[0] ?? [];
    }

    public function save(array $data): bool
    {
        $keys = implode(', ', array_keys($data));
        $values = array_values($data);
        $valuesPlaceholders = [];
        foreach ($data as $v) {
            $valuesPlaceholders[] = '?';
        }
        $valuesPlaceholders = implode(', ', $valuesPlaceholders);
        $stmt = $this->connection->prepare("INSERT INTO {$this->tableName} ($keys) VALUES ($valuesPlaceholders);");

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
            /*'string', */default => 's',
        };
    }
}
