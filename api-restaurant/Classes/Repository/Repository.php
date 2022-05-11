<?php

namespace Repository;

use DB\MySQL;

abstract class Repository
{
    protected object $MySQL;
    private string $table = '';

    /**
     * Repository constructor 
     * @param string $table
     */
    public function __construct($table)
    {
        $this->table = $table;
        $this->MySQL = new MySQL();
    }

    /**
     * @return object MySQL
     */
    public function getMySQL()
    {
        return $this->MySQL;
    }

    /**
     * @param string $name
     * 
     * @return int
     */
    public function insertName($name)
    {
    
        $query = 'INSERT INTO '.$this->table. ' (name) VALUES (:name)';
        
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        
        return $stmt->rowCount();
    }

      /**
     * @param string $name
     * @param int $relationalId
     * 
     * @return int
     */
    public function insertRelational($name, $relationalId)
    {
        $query = "INSERT INTO {$this->table} (name, {$this->relationalColumn}) VALUES (:name, :relational_id)";
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($query);
        $stmt->bindParam(':relational_id', $relationalId);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        
        return $stmt->rowCount();
    }

    /**
     * @param int $id
     * @param string $name
     * 
     * @return int
     */
    public function updateName($id, $name)
    {

        $query = 'UPDATE '.$this->table. ' SET name = :name WHERE id = :id';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->rowCount();

    }

}