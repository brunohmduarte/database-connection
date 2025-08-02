# Database Connection

Componente open source responsável por conectar aplicações web desenvolvidas em PHP com multiplas bases de dados.

## Requisitos
- PHP >=7.0

## Instalação
```shell
composer require brunoduarte/database-connection
```

## Como utilizar
Classe Model: **User.php**
```php
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
```


**./index.php**
```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use BrunoDuarte\DatabaseConnection\ConnectionManager;
use Example\Model\User;

ConnectionManager::initLogger();
ConnectionManager::loadEnv( __DIR__);

try {
    $pdo = ConnectionManager::connect('mysql');
    if (!$pdo instanceof PDO) {
        throw new PDOException("Erro ao conectar ao MySQL");
    }

    $user = new User($pdo);

    // Criar
    // $result = $user->create([
    //     'name'  => 'Joe Coe',
    //     'email' => 'joe.coe@example.com'
    // ]);
    // if ($result) {
    //     echo 'Cadastrado com sucesso!';
    // }
    // Saída: Cadastrado com sucesso!

    // Listar
    // $users = $user->all();
    // foreach ($users as $value) {
    //     echo '<p>'. $value['name'] . ' - ' . $value['email'] . '</p><br/>';
    // }
    // Saída: <p>Joe Coe - joe.coe@example.com</p><br/>

    // Buscar por ID
    // $info = $user->findById(1);
    // echo '<pre>'. print_r($info, true) .'</pre>';
    // Saída: Array ( [user_id] => 1 [name] => Joe Coe [email] => joe.coe@example.com )

    // Buscar por um campo
    // $info = $user->findBy('email', 'joe.coe@example.com');
    // echo '<pre>'. print_r($info, true) .'</pre>';
    // Saída: Array ( [user_id] => 1 [name] => Joe Coe [email] => joe.coe@example.com )

    // Atualizar
    // $result = $user->update(1, ['name' => 'Will Smith']);
    // if ($result) {
    //     echo 'Atualizado com sucesso!';
    // }
    // Saída: Atualizado com sucesso!

    // Deletar
    // $result = $user->delete(1);
    // if ($result) {
    //     echo 'Removido com sucesso!';
    // }
    // Saída: Removido com sucesso!

} catch (Exception $e) {
    echo $e->getMessage();
} catch (PDOException $e) {
    echo '<h1>PDO ERROR:'. $e->getMessage() .'</h1>';
}
```