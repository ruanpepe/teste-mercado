<?php

namespace App\Model\Entity;

use PDOStatement;

class Tax
{
    public int $id;
    public int $id_type;
    public string $name;
    public float $percent;

    /**
     * Retorna todos os impostos de um determinado tipo
     * 
     * @param int $typeId
     * 
     * @return PDOStatement|false
     */
    public static function selectAll(int $typeId): PDOStatement|false
    {
        try {
            $sql = '
                SELECT * FROM taxes 
                WHERE id_type = ' . $typeId;
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->execute();
            return $statement;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Retorna um imposto pelo id
     * 
     * @param int $id
     * 
     * @return PDOStatement|false
     */
    public static function selectById(int $id): PDOStatement|false
    {
        try {
            $sql = '
                SELECT * FROM taxes 
                WHERE id = ' . $id;
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->execute();
            return $statement;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Insere um novo imposto
     * 
     * @return bool
     */
    public function create(): bool
    {
        try {
            $sql = '
                INSERT INTO taxes (
                    id_type, 
                    name, 
                    percent) 
                VALUES (
                    ' . $this->id_type . ', 
                    :name, 
                    ' . $this->percent . ')';
            $statement = DB_CONNECTION->prepare($sql);
            $statement->bindParam(':name', $this->name);
            $statement->execute();
            return true;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Apaga um imposto
     * 
     * @return bool
     */
    public function delete(): bool
    {
        try {
            $sql = '
                DELETE FROM taxes 
                WHERE id = ' . $this->id;
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->execute();
            return true;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
