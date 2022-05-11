<?php

include 'bootstrap.php';

use Util\GenericConstantsUtil;
use Util\RoutesUtil;
use Validator\RequestValidator;
use Util\JsonUtil;

try {

    $RequestValidator = new RequestValidator(RoutesUtil::getRoute());
    $return = $RequestValidator->processRequest();
  
    $JsonUtil = new JsonUtil;
    $JsonUtil->processArrayToReturn($return);
   
} catch (Exception $e) {
    echo json_encode([
        GenericConstantsUtil::TYPE => GenericConstantsUtil::TYPE_ERROR,
        GenericConstantsUtil::RESPONSE => $e->getMessage()
    ], JSON_THROW_ON_ERROR);
    exit;
}