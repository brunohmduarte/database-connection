<?php

namespace BrunoDuarte\DatabaseConnection;

use PDO;

trait CrudTrait
{
    protected PDO $db;
    protected string $table;
    protected string $tableId;

    public function setConnection(PDO $pdo): void
    {
        $this->db = $pdo;
    }

    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->query($sql)->fetchAll() ?: [];
    }

    public function findById($id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->tableId} = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findBy(string $field, $value): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$field} = :value";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['value' => $value]);
        $return = $stmt->fetch();

        return $return ?: [];
    }

    public function create(array $data): bool
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_map(fn($key) => ":$key", array_keys($data)));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($data);
    }

    public function update($id, array $data): bool
    {
        $set = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $data['id'] = $id;

        $sql = "UPDATE {$this->table} SET $set WHERE {$this->tableId} = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($data);
    }

    public function delete($id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->tableId} = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }
}
