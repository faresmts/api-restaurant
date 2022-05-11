<?php

namespace Repository;

use DB\MySQL;
use Util\GenericConstantsUtil;
use InvalidArgumentException;

class AuthTokensRepository
{
    private object $MySQL;
    public const TABLE = 'token';

    public function __construct()
    {
        $this->MySQL = new MySQL();
    }

    /**
     * @param string $token
     * 
     * @return [type]
     */
    public function validateToken($token)
    {
        $token = str_replace([' ', 'Bearer'], '', $token);
        
        if($token){
            $query = 'SELECT id FROM ' . self::TABLE . ' WHERE token = :token and status = :status';
            $stmt = $this->getMySQL()->getDb()->prepare($query);
            $stmt->bindValue(':status', GenericConstantsUtil::YES);
            $stmt->bindValue(':token', $token);
            $stmt->execute();
            
            if($stmt->rowCount() !== 1){
                http_response_code(401);
                throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_UNAUTH_TOKEN);
            }
        }  
        else{
            throw new InvalidArgumentException(GenericConstantsUtil::MSG_ERROR_EMPTY_TOKEN);
        }
    }

    /**
     * @return object MySQL
     */
    public function getMySQL()
    {
        return $this->MySQL;
    }
}
