<?php

// CONEXION A LA BASE DE DATOS CON PDO

function conexionPDO(): PDO {
    $dsn = 'mysql:host=localhost;dbname=todo_list;charset=utf8mb4';
    $username = 'root';
    $password = 'laura';
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $username, $password);
        return $pdo;
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}