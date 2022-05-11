<?php

namespace Util;

use InvalidArgumentException;
use JsonException as JsonExceptionAlias;

class JsonUtil
{

    /**
     * @param array $return
     * 
     */
    public function processArrayToReturn($return)
    {
       
        $data = [];
        $data[GenericConstantsUtil::TYPE] = GenericConstantsUtil::TYPE_ERROR;
        
        if(is_array($return) && count($return) > 0 || strlen($return) > 10){
            $data[GenericConstantsUtil::TYPE] = GenericConstantsUtil::TYPE_SUCESS;
            $data[GenericConstantsUtil::RESPONSE] = $return;
        }

        $this->returnJson($data);
    }

    /**
     * @param mixed $json
     * 
     * @return JSON 
     */
    private function returnJson($json)
    {
        header('Content-Type', 'application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

        echo json_encode($json);
        exit;
    }

    /**
     * @return array
     */
    public static function processBodyRequestToJson()
    {
        try {
            $postJson = json_decode(file_get_contents('php://input'), true);
        } catch (JsonExceptionAlias $e) {
            throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_EMPTY_JSON);
        }
       
        if(is_array($postJson) && count($postJson) > 0) {
            return $postJson;
        }

    }
}