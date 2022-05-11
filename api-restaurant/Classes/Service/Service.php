<?php

namespace Service;


use Util\GenericConstantsUtil;
use InvalidArgumentException;

abstract class Service
{
    protected array $data;
    protected array $requestBodyData = [];

    public const GET_RESOURCES = ['list'];
    public const DELETE_RESOURCES = ['delete'];
    public const POST_RESOURCES = ['create'];
    public const PUT_RESOURCES = ['update'];

    /**
     * Service Constructor
     * 
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * @return method 
     */
    public function validateGet()
    {
        $return = null;
        $resource = $this->data['resource'];

        if(in_array($resource, self::GET_RESOURCES, true)){
            if(is_numeric($this->data['id'])  || $this->data['id'] === null){
                $return = $this->data['id'] > 0 ? $this->getOneByKey() : $this->$resource();
            }else{
                throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_ID);
            }
        }else{
            throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_NO_RECORD_FOUND);
        }

        $this->validateReturnRequest($return);

        return $return;
    }

    /**
     * @return method 
     */
    public function validateDelete()
    {
        $return = null;
        $resource = $this->data['resource'];

        if(in_array($resource, self::DELETE_RESOURCES, true)){
            $return = $this->validateRequiredId($resource);
        }else{
            throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_NO_RECORD_FOUND);
        }
        
        $this->validateReturnRequest($return);
        
        return $return;
    }

    /**
     * @return method 
     */
    public function validatePost()
    {
        $return = null;
        $resource = $this->data['resource'];

        if(in_array($resource, self::POST_RESOURCES, true)){
            $return = $this->$resource();
        }else{
            throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_NO_RECORD_FOUND);
        }

        $this->validateReturnRequest($return);

        return $return;
    }
    
    /**
     * @return method 
     */
    public function validatePut()
    {
        $return = null;
        $resource = $this->data['resource'];

        if(in_array($resource, self::PUT_RESOURCES, true)){
            $return = $this->validateRequiredId($resource);
        }else{
            throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_NO_RECORD_FOUND);
        }

        $this->validateReturnRequest($return);

        return $return;
    }

    /**
     * @return array
     */
    abstract protected function getOneByKey();

    /**
     * @param array $dataRequest
     */
    public function setRequestBodyData($dataRequest)
    {
        $this->requestBodyData = $dataRequest;
    }
    
    /**
     * @param array $return
     */
    protected function validateReturnRequest($return)
    {
        if($return === null){
            throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_GENERAL);
        }
    }

    /**
     * @param array $resource
     * 
     * @return method
     */
    protected function validateRequiredId($resource)
    {
        if($this->data['id'] > 0){
            $return = $this->$resource();
        }else{
            throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_ID);
        }

        return $return;
    }
}