<?php

namespace Service;

use Repository\MenuRepository;
use Util\GenericConstantsUtil;
use InvalidArgumentException;

class MenuService extends Service
{
    public const TABLE = 'menu';
    public const SUPER_COLUMN = 'store_id';
    private object $MenuRepository;

     /**
     * @param array $data
     * MenuService Constructor
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->MenuRepository = new MenuRepository();
    }

     /**
     * @return array
     */
    protected function getOneByKey()
    {
        $return = $this->MenuRepository->getMySQL()->getOneByKey(self::TABLE, $this->data['id']);
        $menuId = intval($return["id"]);
        
        $categories = $this->MenuRepository->getMySQL()->getAllCategories($menuId);
        if($categories){
            $return["categories"] = $categories;
        }
        
        return $return;
    }

    /**
     * @return array
     */
    protected function list()
    {
        return $this->MenuRepository->getMySQL()->getAll(self::TABLE);
    }
    
     /**
     * @return string
     */
    protected function delete()
    {
        return $this->MenuRepository->getMySQL()->delete(self::TABLE, $this->data['id']);
    }

    /**
     * @return array
     */
    protected function create()
    {
        [$name, $storeId]  = [$this->requestBodyData['name'], intval($this->requestBodyData['store_id'])];
        
        if($name && $storeId){
         
            if($this->MenuRepository->getMySQL()->getOneByKey('store', $storeId) > 0){
                if($this->MenuRepository->insertRelational($name, $storeId) > 0){
                    $insertedNameId = $this->MenuRepository->getMySQL()->getDb()->lastInsertId();
                    $this->MenuRepository->getMySQL()->getDb()->commit();
                    return ['inserted_name_id' => $insertedNameId];
                }
            }
            
            $this->MenuRepository->getMySQL()->getDb()->rollback();
            throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_GENERAL);
        }

        throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_NAME);
    }
    

    /**
     * @return string
     */
    protected function update()
    {
        [$menuId, $name, $storeId] = [intval($this->data['id']) , $this->requestBodyData['name'], intval($this->requestBodyData['store_id'])];

        if($this->MenuRepository->getMySQL()->getRelational($storeId, $menuId, self::TABLE, self::SUPER_COLUMN) > 0){
            if($this->MenuRepository->updateName($menuId, $name) > 0){
                $this->MenuRepository->getMySQL()->getDb()->commit();
                return GenericConstantsUtil::MSG_SUCESS_UPDATE;
            }
        }

        $this->MenuRepository->getMySQL()->getDb()->rollback();
        throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_NO_RECORDS_AFFECTED);
        
    }

   
}