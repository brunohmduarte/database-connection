<?php

namespace BrunoDuarte\DatabaseConnection;

use PDO;

trait CrudTrait
{
    /**
     * Instância da conexão PDO
     * @var PDO
     */
    protected PDO $db;

    /**
     * Nome da tabela
     * @var string
     */
    protected string $table;

    /**
     * Nome do campo identificador da tabela
     * @var string
     */
    protected string $tableId;

    /**
     * Seta a conexão PDO
     * 
     * @param \PDO $pdo Instância da conexão PDO
     * @return void
     */
    public function setConnection(PDO $pdo): void
    {
        $this->db = $pdo;
    }

    /**
     * Retorna todos os registros da tabela
     * 
     * @return array
     */
    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->query($sql)->fetchAll() ?: [];
    }

    /**
     * Busca um registro pelo ID
     * 
     * @param int $id ID do registro
     * @return array|null
     */
    public function findById($id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->tableId} = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Busca um registro por um campo específico
     * 
     * @param string $field Nome do campo na tabela do banco de dados
     * @param mixed $value Valor do campo
     * @return array
     */
    public function findBy(string $field, $value): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$field} = :value";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['value' => $value]);
        $return = $stmt->fetch();

        return $return ?: [];
    }

    /**
     * Cria um novo registro
     * 
     * @param array $data Array associativo com os dados para inserir no formato 'campo' => 'valor'
     * @return bool
     */
    public function create(array $data): bool
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_map(fn($key) => ":$key", array_keys($data)));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($data);
    }

    /**
     * Atualiza um registro
     * 
     * @param int $id ID do registro
     * @param array $data Array associativo com os dados para atualizar no formato 'campo' => 'valor'
     * @return bool
     */
    public function update($id, array $data): bool
    {
        $set = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $data['id'] = $id;

        $sql = "UPDATE {$this->table} SET $set WHERE {$this->tableId} = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($data);
    }

    /**
     * Deleta um registro
     * 
     * @param int $id ID do registro
     * @return bool
     */
    public function delete($id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->tableId} = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }
}
