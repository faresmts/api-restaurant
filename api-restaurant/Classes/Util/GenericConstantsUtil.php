<?php

namespace Util;

abstract class GenericConstantsUtil
{
    /* REQUESTS */
    public const TYPE_REQUEST = ['GET', 'POST', 'DELETE', 'PUT'];
    public const TYPE_GET = ['STORE', 'MENU', 'CATEGORY', 'ITEM'];
    public const TYPE_POST = ['STORE','MENU', 'CATEGORY', 'ITEM'];
    public const TYPE_DELETE = ['STORE','MENU', 'CATEGORY', 'ITEM'];
    public const TYPE_PUT = ['STORE', 'MENU','CATEGORY', 'ITEM'];

    /* ERRORS */
    public const MSG_ERROR_ROUTE = 'rout not allowed';
    public const MSG_ERROR_RESOURCE = 'non-existent resource';
    public const MSG_ERROR_GENERAL = 'some error occurred';
    public const MSG_ERROR_NO_RECORD_FOUND = 'no record found';
    public const MSG_ERROR_NO_RECORDS_AFFECTED = 'no records affected';
    public const MSG_ERROR_EMPTY_TOKEN= 'token is required';
    public const MSG_ERROR_UNAUTH_TOKEN = 'unauthorized token';
    public const MSG_ERROR_EMPTY_JSON = 'empty body';

    /* SUCESS */
    public const MSG_SUCESS_DELETED = 'record deleted';
    public const MSG_SUCESS_UPDATE = 'record updated';

    /* RESOURCE */
    public const MSG_ERROR_ID = 'a valid id is required';
    public const MSG_ERROR_LOGIN_PASSWORD = 'login and password is required';
    public const MSG_ERROR_NAME = 'name is required';

    /* JSON RETURN  */
    const TYPE_SUCESS = 'sucess';
    const TYPE_ERROR = 'error';

    /* MISCELLANEOUS */
    public const YES = 'Y';
    public const TYPE = 'type';
    public const RESPONSE = 'response';
}