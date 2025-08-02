<?php

require './vendor/autoload.php';
require 'Model/User.php';

use BrunoDuarte\DatabaseConnection\ConnectionManager;
use Example\Model\User;

// Inicia o logger e carrega as variáveis de ambiente
ConnectionManager::initLogger();
ConnectionManager::loadEnv(__DIR__.'/../');

try {
    // Realiza a conexão com o banco de dados MySQL
    $pdo = ConnectionManager::connect('mysql');
    if (!$pdo instanceof PDO) {
        throw new \Exception("Erro ao conectar ao MySQL");
    }
    
    $user = new User($pdo);

    // Listagem
    $users = $user->all();
    foreach ($users as $key => $value) {
        echo '<p>'. $key . ' - ' . $value['name'] . ' - ' . $value['email'] . '</p><br/>';
    }

    // Busca por ID
    // $info = $user->findById(1);
    // echo '<pre>'. print_r($info, true) .'</pre>';


    // Busca por e-mail
    // $info = $user->findBy('email', 'joe.coe@example.com');
    // echo '<pre>'. print_r($info, true) .'</pre>';

} catch (\PDOException $e) {
    echo "MySQL: " . $e->getMessage() . PHP_EOL;
}