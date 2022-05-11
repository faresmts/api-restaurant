<?php

namespace DB;

use InvalidArgumentException;
use PDO;
use PDOException;
use Util\GenericConstantsUtil;

class MySQL
{
    private object $db;

    /**
     * MySQL constructor.
     */
    public function __construct()
    {
        $this->db = $this->setDB();
    }

    /**
     * @return object|PDO
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @return PDO
     */
    public function setDB()
    {
        try {
            return new PDO(
                'mysql:host=' . HOST . '; dbname=' . DB . ';', USER, PASSWORD
            );
        } catch (PDOException $exception) {
            throw new PDOException($exception->getMessage());
        }
    }

    /**
     * @param string $table
     * @param int $id
     * @return string
     */
    public function delete($table, $id)
    {
        $queryDelete = 'DELETE FROM ' . $table . ' WHERE id = :id';
        if ($table && $id) {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($queryDelete);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $this->db->commit();
                return GenericConstantsUtil::MSG_SUCESS_DELETED;
            }
            $this->db->rollBack();
            throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_NO_RECORD_FOUND);
        }
        throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_GENERAL);
    }

    /**
     * @param string $table
     * @return array
     */
    public function getAll($table)
    {
        if ($table) {
            
            $query = 'SELECT * FROM ' . $table;
            $stmt = $this->db->query($query);
            $records = $stmt->fetchAll($this->db::FETCH_ASSOC);
            if (is_array($records) && count($records) > 0) {
                return $records;
            }
        }
        throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_GENERAL);
    }

    /**
     * @param string $table
     * @param int $id
     * @return mixed
     */
    public function getOneByKey($table, $id)
    {
        
        if ($table && $id) {
          
            $query = 'SELECT * FROM ' . $table . ' WHERE id = :id';
           
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $totalRecords = $stmt->rowCount();
            if ($totalRecords === 1) {
                return $stmt->fetch($this->db::FETCH_ASSOC);
            }
            throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_NO_RECORD_FOUND);
        }

        throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_ID);
    }

    /**
     * @param int $superId
     * @param int $sonId
     * @param string $table
     * @param string $superColumn
     * 
     * @return [type]
     */
    public function getRelational($superId, $sonId, $table, $superColumn){
        
        if ($superId && $sonId && $table && $superColumn) {
            
            $query = "SELECT * FROM {$table} WHERE (({$superColumn} = :super_id) AND (id = :son_id))";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':son_id',$sonId);
            $stmt->bindParam(':super_id', $superId);
            $stmt->execute();
            $totalRecords = $stmt->rowCount();
            if ($totalRecords === 1) {
                return $stmt->fetch($this->db::FETCH_ASSOC);
            }
            throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_NO_RECORD_FOUND);
        }

        throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_ID);
    }

    /**
     * @param int $containerId
     * @param string $table
     * @param string $columnName
     * 
     * @return [type]
     */
    public function getAllByKey($containerId, $table, $columnName)
    {

        if ($containerId) {
            
            $query = "SELECT * FROM {$table} WHERE {$columnName} = :container_id";
     
          
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':container_id', $containerId);
            $stmt->execute();
            $totalRecords = $stmt->rowCount();

            if ($totalRecords > 0) {
                return $stmt->fetchAll($this->db::FETCH_ASSOC);
            }
            return false;
        }

        throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_ID);
    }

    /**
     * @param int $menuId
     * 
     * @return [type]
     */
    public function getAllCategories($menuId){
        
        if ($menuId) {

            $query = 'SELECT * FROM category WHERE menu_id = :menu_id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':menu_id', $menuId);
            $stmt->execute();
            $totalRecords = $stmt->rowCount();
            
            if ($totalRecords > 0) {
                $return = $stmt->fetchAll($this->db::FETCH_ASSOC);
                for($i = 0; $i < count($return); $i++){
                    $categoryId = $return[$i]["id"];
                    $items = $this->getAllByKey($categoryId, 'item', 'category_id');
                    if($items){
                        $return[$i] += $items;
                    }
                }
                return $return;
            }
            return false;
        }

        throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_ID);
    }

}