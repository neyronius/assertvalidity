<?php

error_reporting(-1);
ini_set('display_errors', 'on');

require 'vendor/autoload.php';

/**
 * 
 * @staticvar null $argentum
 * @return \AssertValidity\Argumentum
 */
function validity()
{
    static $argumentum = null;
    
    if($argumentum === null){
        
        $v = new \AssertValidity\Validatum;
        $argumentum = new AssertValidity\Argumentum;
        $argumentum->setValidator($v);
    }
    
    return $argumentum;
}


/**
 * 
 * @param int  $a
 * @param string $b
 */
function test($a, $b){
    validity()->check(__METHOD__, func_get_args());
}

test(1, "1");
