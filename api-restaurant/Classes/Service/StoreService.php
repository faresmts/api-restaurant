<?php

namespace Service;

use Repository\StoreRepository;
use Util\GenericConstantsUtil;
use InvalidArgumentException;

class StoreService extends Service
{
    public const TABLE = 'store';
    public const SON_TABLE = 'menu';
    public const FK_COLUMN = 'store_id';
    private object $StoreRepository;

    /**
     * @param array $data
     * StoreService Constructor
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->StoreRepository = new StoreRepository();
    }

    /**
     * @return array
     */
    protected function getOneByKey()
    {
        $return = $this->StoreRepository->getMySQL()->getOneByKey(self::TABLE, $this->data['id']);
        $storeId = intval($return["id"]);
        
        $menus = $this->StoreRepository->getMySQL()->getAllByKey($storeId, self::SON_TABLE, self::FK_COLUMN );

        if($menus){
            $return["menus"] = $menus;
        }

        return $return; 
    }

    /**
     * @return array
     */
    protected function list()
    {
        return $this->StoreRepository->getMySQL()->getAll(self::TABLE);
    }

    
    /**
     * @return string
     */
    protected function delete()
    {
        return $this->StoreRepository->getMySQL()->delete(self::TABLE, $this->data['id']);
    }

    /**
     * @return array
     */
    protected function create()
    {
        $name = $this->requestBodyData['name'];
        if($name){

            if($this->StoreRepository->insertName($name) > 0){
                $insertedNameId = $this->StoreRepository->getMySQL()->getDb()->lastInsertId();
                $this->StoreRepository->getMySQL()->getDb()->commit();
                return ['inserted_name_id' => $insertedNameId];
            }

            $this->StoreRepository->getMySQL()->getDb()->rollback();
            throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_GENERAL);
        }

        throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_NAME);
    }


    /**
     * @return string
     */
    protected function update()
    {
        [$id, $name] = [$this->data['id'] , $this->requestBodyData['name']];
     
        if($this->StoreRepository->updateName($id, $name) > 0){
            $this->StoreRepository->getMySQL()->getDb()->commit();
            return GenericConstantsUtil::MSG_SUCESS_UPDATE;
        }
        
        $this->StoreRepository->getMySQL()->getDb()->rollback();
        throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_NO_RECORDS_AFFECTED);
        
    }

   
}