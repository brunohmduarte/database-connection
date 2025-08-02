<?php

namespace Example\Model;

use BrunoDuarte\DatabaseConnection\CrudTrait;

class User
{
    use CrudTrait;
    
    public function __construct(\PDO $pdo)
    {
        $this->setConnection($pdo);
        $this->table = 'users';
        $this->tableId = 'user_id';
    }
}