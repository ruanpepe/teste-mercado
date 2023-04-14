<?php

namespace App\Config;

use PDO, PDOException;

class DBConnection
{
    public PDO $connection;

    /**
     * Retorna a conexão com o banco de dados
     * 
     * @param string $driver
     * @param string $host
     * @param int $port
     * @param string $user
     * @param string $password
     * @param ?string $dbName
     * 
     * @return PDO
     */
    public static function getConnection(string $driver, string $host, int $port, string $user, string $password, ?string $dbName = 'teste_mercado'): PDO
    {
        try {
            $DBConnection = new DBConnection();
            $DBConnection->connection = new PDO($driver . ':host=' . $host . ';port=' . $port . ';dbname=' . $dbName, $user, $password);
            $DBConnection->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $DBConnection->connection;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
