<?php

namespace Service;

use Repository\ItemRepository;
use Util\GenericConstantsUtil;
use InvalidArgumentException;

class ItemService extends Service
{
    public const TABLE = 'item';
    public const SUPER_COLUMN = 'category_id';
    private object $ItemRepository;

      /**
     * @param array $data
     * ItemService Constructor
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->ItemRepository = new ItemRepository();
    }

    /**
     * @return array
     */
    protected function getOneByKey()
    {
        return $this->ItemRepository->getMySQL()->getOneByKey(self::TABLE, $this->data['id']);
    }

     /**
     * @return array
     */
    protected function list()
    {
        return $this->ItemRepository->getMySQL()->getAll(self::TABLE);
    }

    /**
     * @return string
     */
    protected function delete()
    {
        return $this->ItemRepository->getMySQL()->delete(self::TABLE, $this->data['id']);
    }

    /**
     * @return array
     */
    protected function create()
    {
        [$name, $price, $categoryId] = [$this->requestBodyData['name'], floatval($this->requestBodyData['price']), intval($this->requestBodyData['category_id'])];
        
        if($name && $price && $categoryId){
            if($this->ItemRepository->getMySQL()->getOneByKey('category', $categoryId) > 0){

                if( $this->ItemRepository->insertItem($name, $price, $categoryId) > 0){
                   
                    $insertedNameId = $this->ItemRepository->getMySQL()->getDb()->lastInsertId();
                    $this->ItemRepository->getMySQL()->getDb()->commit();
                    return ['inserted_name_id' => $insertedNameId];
                }
            }
            
            $this->ItemRepository->getMySQL()->getDb()->rollback();
            throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_GENERAL);
        }

        throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_NAME);
    }
    
    /**
     * @return string
     */
    protected function update()
    {
        $itemId = $this->data['id'] ;
        $name = $this->requestBodyData['name'];
        $price = $this->requestBodyData['price'];
        $categoryId =$this->requestBodyData['category_id']; 
      
        if($itemId && $name && $price && $categoryId){

            if($this->ItemRepository->getMySQL()->getRelational($categoryId, $itemId, self::TABLE, self::SUPER_COLUMN) > 0){
                if($this->ItemRepository->updateItem($itemId, $name, $price) > 0){
                    $this->ItemRepository->getMySQL()->getDb()->commit();
                    return GenericConstantsUtil::MSG_SUCESS_UPDATE;
                }
            }
        }
        
        $this->ItemRepository->getMySQL()->getDb()->rollback();
        throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_NO_RECORDS_AFFECTED);
        
    }

}