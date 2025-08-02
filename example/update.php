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
    
    // Atualização
    $result = $user->update(1, ['name' => 'Will Smith']);
    if ($result) {
        echo 'Atualizado com sucesso!' . PHP_EOL;
    }

    // Remoção
    // $result = $user->delete(3);
    // if ($result) {
    //     echo 'Removido com sucesso!' . PHP_EOL;
    // }

} catch (\PDOException $e) {
    echo "MySQL: " . $e->getMessage() . PHP_EOL;
}