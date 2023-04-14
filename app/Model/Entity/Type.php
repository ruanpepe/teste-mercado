<?php

namespace App\Model\Entity;

use PDOStatement;

class Type
{
    public int $id;
    public string $name;

    /**
     * Retorna todos os tipos
     * 
     * @return PDOStatement|false
     */
    public static function selectAll(): PDOStatement|false
    {
        try {
            $sql = '
                SELECT * FROM types';
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->execute();
            return $statement;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Retorna todos os tipos com os impostos
     * 
     * @return PDOStatement|false
     */
    public static function selectAllWithTaxes(): PDOStatement|false
    {
        try {
            $sql = '
                SELECT types.*, sum(taxes.percent) as tax 
                FROM types 
                LEFT JOIN taxes ON types.id = taxes.id_type 
                GROUP BY types.id';
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->execute();
            return $statement;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Retorna um tipo pelo id
     * 
     * @param int $id
     * 
     * @return PDOStatement|false
     */
    public static function selectById(int $id): PDOStatement|false
    {
        try {
            $sql = '
                SELECT * FROM types 
                WHERE id = ' . $id;
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->execute();
            return $statement;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Insere um novo tipo
     * 
     * @return bool
     */
    public function create(): bool
    {
        try {
            $sql = '
                INSERT INTO types (name) 
                VALUES (:name)';
            $statement = DB_CONNECTION->prepare($sql);
            $statement->bindParam(':name', $this->name);
            $statement->execute();
            return true;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Atualiza um tipo
     * 
     * @return bool
     */
    public function update(): bool
    {
        try {
            $sql = '
                UPDATE types 
                SET name = :name 
                WHERE id = ' . $this->id;
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->bindParam(':name', $this->name);
            $statement->execute();
            return true;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Apaga um registro na tabela tipos
     * 
     * @return bool
     */
    public function delete(): bool
    {
        try {
            $sql = '
                DELETE FROM types 
                WHERE id = ' . $this->id;
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->execute();
            return true;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
