<?php

namespace Repository;

class CategoryRepository extends Repository
{

    protected string $relationalColumn = 'menu_id';

    /**
     * CategoryRepository Constructor
     */
    public function __construct()
    {
        parent::__construct('category');
    }
    
}