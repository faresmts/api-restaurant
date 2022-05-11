<?php

namespace Repository;

class ItemRepository extends Repository
{ 

    /**
     * ItemRepository constructor
     */
    public function __construct()
    {
        parent::__construct('item');
    }


    /**
     * @param int $id
     * @param string $name
     * @param float $price
     * 
     * @return [type]
     */
    public function updateItem($id, $name, $price)
    {
        $query = 'UPDATE item SET price = :price WHERE id = :id; UPDATE item SET name = :name WHERE id = :id;';
        
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($query);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->execute();     

        return ($stmt->rowCount());

    }


    /**
     * @param string $name
     * @param float $price
     * @param int $categoryId
     * 
     * @return [type]
     */
    public function insertItem($name, $price, $categoryId)
    {
    
        $query = 'INSERT INTO '.$this->table. ' (name, price, category_id) VALUES (:name, :price, :category_id)';
        
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();
        
        return $stmt->rowCount();
    }
}