<?php

namespace Repository;

class MenuRepository extends Repository
{

    protected string $relationalColumn = 'store_id';

    /**
     * MenuRepository constructor
     */
    public function __construct()
    {
        parent::__construct('menu');
    }
    
}