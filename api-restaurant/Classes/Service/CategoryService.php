<?php

namespace Service;

use Repository\CategoryRepository;
use Util\GenericConstantsUtil;
use InvalidArgumentException;

class CategoryService extends Service 
{
    public const TABLE = 'category';
    public const SON_TABLE = 'item';
    public const FK_COLUMN = 'category_id';
    public const SUPER_COLUMN = 'menu_id';
    private object $CategoryRepository;

    /**
     * @param array $data
     * CategoryService Constructor
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->CategoryRepository = new CategoryRepository();
    }

    /**
     * @return array
     */
    protected function getOneByKey()
    {
        $return = $this->CategoryRepository->getMySQL()->getOneByKey(self::TABLE, $this->data['id']);

        $categoryId = intval($return["id"]);
        $items = $this->CategoryRepository->getMySQL()->getAllByKey($categoryId, self::SON_TABLE, self::FK_COLUMN);
        if($items){
            $return["items"] = $items; 
        }

        return $return;
    }

    /**
     * @return array
     */
    protected function list()
    {
        return $this->CategoryRepository->getMySQL()->getAll(self::TABLE); 
    }
    
    /**
     * @return string
     */
    protected function delete()
    {
        return $this->CategoryRepository->getMySQL()->delete(self::TABLE, $this->data['id']);
    }

    /**
     * @return array
     */
    protected function create()
    {
        [$name, $menuId]  = [$this->requestBodyData['name'], intval($this->requestBodyData['menu_id'])];
        
        
        if($name && $menuId){

            if($this->CategoryRepository->getMySQL()->getOneByKey('menu', $menuId) > 0){
                if($this->CategoryRepository->insertRelational($name, $menuId) > 0){
                    $insertedNameId = $this->CategoryRepository->getMySQL()->getDb()->lastInsertId();
                    $this->CategoryRepository->getMySQL()->getDb()->commit();
                    return ['inserted_name_id' => $insertedNameId];
                }
            }
            
            $this->CategoryRepository->getMySQL()->getDb()->rollback();
            throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_GENERAL);
        }

        throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_NAME);
    }

    /**
     * @return string
     */
    protected function update()
    {
        [$categoryId, $name, $menuId] = [intval($this->data['id']) , $this->requestBodyData['name'], intval($this->requestBodyData['menu_id'])];
        
        if($this->CategoryRepository->getMySQL()->getRelational($menuId, $categoryId, self::TABLE, self::SUPER_COLUMN) > 0){
            
            if($this->CategoryRepository->updateName($categoryId, $name) > 0){
                $this->CategoryRepository->getMySQL()->getDb()->commit();
                return GenericConstantsUtil::MSG_SUCESS_UPDATE;
            }

        }
        $this->CategoryRepository->getMySQL()->getDb()->rollback();
        throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_NO_RECORDS_AFFECTED);
    }

}