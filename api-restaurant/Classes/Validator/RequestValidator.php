<?php

namespace Validator;

use InvalidArgumentException;
use Util\GenericConstantsUtil;
use Util\JsonUtil;
use Repository\AuthTokensRepository;
use Service\StoreService;
use Service\MenuService;
use Service\CategoryService;
use Service\ItemService;

class RequestValidator
{
    private array $request;
    private array $dataRequest = [];
    private object $AuthTokensRepository;

    const GET = 'GET';
    const DELETE = 'DELETE';
    const STORE = 'STORE';
    const MENU = 'MENU';
    const CATEGORY = 'CATEGORY';
    const ITEM = 'ITEM';

    /**
     * RequestValidator Construct
     * @param array $request
     */
    public function __construct($request){
        $this->request = $request;
        $this->AuthTokensRepository = new AuthTokensRepository();
    }

    /**
     * @return array|string 
     */
    public function processRequest()
    {
        $return = utf8_encode(GenericConstantsUtil::MSG_ERROR_ROUTE);

        if(in_array($this->request['method'], GenericConstantsUtil::TYPE_REQUEST, true)){
            $return = $this->directRequest();
        }

        return $return;
    }

    /**
     * @return method
     */
    private function directRequest()
    {
        if($this->request['method'] !== self::GET && $this->request['method'] !== self::DELETE){
            $this->dataRequest = JsonUtil::processBodyRequestToJson();
        }
        
        if(isset(getallheaders()['Authorization'])){
            $this->AuthTokensRepository->validateToken(getallheaders()['Authorization']);
        }else{
            http_response_code(401);
            throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_EMPTY_TOKEN);
        }
    
        $method = $this->request['method'];

        return $this->$method();
    }


    /**
     * @return array|string
     */
    private function get()
    {
        $return = utf8_encode(GenericConstantsUtil::MSG_ERROR_ROUTE);

        if(in_array($this->request['route'], GenericConstantsUtil::TYPE_GET, true)){

            switch($this->request['route']){
                case self::STORE:
                    $StoreService = new StoreService($this->request);
                    $return = $StoreService->validateGet();
                    break;
                case self::MENU:
                    $MenuService = new MenuService($this->request);
                    $return = $MenuService->validateGet();
                    break;
                case self::CATEGORY:
                    $CategoryService = new CategoryService($this->request);
                    $return = $CategoryService->validateGet();
                    break;
                case self::ITEM:
                    $ItemService = new ItemService($this->request);
                    $return = $ItemService->validateGet();
                    break;
                default:
                    throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_RESOURCE);
            }
        }

        return $return;
    }
    
    /**
     * @return array|string
     */
    private function delete()
    {
        $return = utf8_encode(GenericConstantsUtil::MSG_ERROR_ROUTE);

        if(in_array($this->request['route'], GenericConstantsUtil::TYPE_DELETE, true)){
            
            switch($this->request['route']){
                case self::STORE:
                    $StoreService = new StoreService($this->request);
                    $return = $StoreService->validateDelete();
                    break;
                case self::MENU:
                    $MenuService = new MenuService($this->request);
                    $return = $MenuService->validateDelete();
                    break;
                case self::CATEGORY:
                    $CategoryService = new CategoryService($this->request);
                    $return = $CategoryService->validateDelete();
                    break;
                case self::ITEM:
                    $ItemService = new ItemService($this->request);
                    $return = $ItemService->validateDelete();
                    break;
                default:
                    throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_RESOURCE);
            }
        }

        return $return;
    }

    /**
     * @return array|string
     */
    private function post()
    {
        $return = utf8_encode(GenericConstantsUtil::MSG_ERROR_ROUTE);

        if(in_array($this->request['route'], GenericConstantsUtil::TYPE_POST, true)){
            
            switch($this->request['route']){
                case self::STORE:
                    $StoreService = new StoreService($this->request);
                    $StoreService->setRequestBodyData($this->dataRequest);
                    $return = $StoreService->validatePost();
                    break;
                case self::MENU:
                    $MenuService = new MenuService($this->request);
                    $MenuService->setRequestBodyData($this->dataRequest);
                    $return = $MenuService->validatePost();
                    break;
                case self::CATEGORY:
                    $CategoryService = new CategoryService($this->request);
                    $CategoryService->setRequestBodyData($this->dataRequest);
                    $return = $CategoryService->validatePost();
                    break;
                case self::ITEM:
                    $ItemService = new ItemService($this->request);
                    $ItemService->setRequestBodyData($this->dataRequest);
                    $return = $ItemService->validatePost();
                    break;
                default:
                    throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_RESOURCE);
            }
        }

        return $return;
    }

    /**
     * @return array|string
     */
    private function put()
    {
        $return = utf8_encode(GenericConstantsUtil::MSG_ERROR_ROUTE);

        if(in_array($this->request['route'], GenericConstantsUtil::TYPE_PUT, true)){
            
            switch($this->request['route']){
                case self::STORE:
                    $StoreService = new StoreService($this->request);
                    $StoreService->setRequestBodyData($this->dataRequest);
                    $return = $StoreService->validatePut();
                    break;
                case self::MENU:
                    $MenuService = new MenuService($this->request);
                    $MenuService->setRequestBodyData($this->dataRequest);
                    $return = $MenuService->validatePut();
                    break;
                case self::CATEGORY:
                    $CategoryService = new CategoryService($this->request);
                    $CategoryService->setRequestBodyData($this->dataRequest);
                    $return = $CategoryService->validatePut();
                    break;
                case self::ITEM:
                    $ItemService = new ItemService($this->request);
                    $ItemService->setRequestBodyData($this->dataRequest);
                    $return = $ItemService->validatePut();
                    break;
                default:
                    throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_RESOURCE);
            }
        }

        return $return;
    }
}