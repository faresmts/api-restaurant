<?php

namespace Util;

class RoutesUtil
{

    /**
     * @return array $request
     */
    public static function getRoute()
    {
        $urls = self::getUrl();
        
        $request = [];
        $request['route'] = strtoupper($urls[0]);
        $request['resource'] = $urls[1] ?? null;
        $request['id'] = $urls[2] ?? null;
        $request['method'] = $_SERVER['REQUEST_METHOD'];
    
        return $request;

    }

    /**
     * @return string $url
     */
    public static function getUrl()
    {
        $uri = str_replace('/' . DIR_PROJECT, '', $_SERVER['REQUEST_URI']);
        return explode('/', trim($uri, '/'));
    }

}