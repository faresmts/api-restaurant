<?php


/**
 * AUTOLOAD FOR 'Classes'
 * @param $classe
 */
function autoload($class)
{
    $dir = DIR_APP . DS;
    $class = $dir . 'Classes' . DS . str_replace('\\', DS, $class) . '.php';
    if (file_exists($class) && !is_dir($class)) {
        include $class;
    }
}

spl_autoload_register('autoload');

