<?php

error_reporting(-1);
ini_set('display_errors', 'on');

require 'vendor/autoload.php';

//ArgCheck\Checker::config();
//
//class Test {
//
//    /**
//     * 
//     * @param int $a
//     * @param string $b
//     */
//    public function testCheck($a, $b)
//    {
//        argcheck(__METHOD__, func_get_args());
//    }
//
//}
//
//$t = new Test;
//
//$t->testCheck(1, 2);

//AssertValidity\Arguments::test();

$v = new AssertValidity\Validatum();

$v->add('int', function($v){
    
    return is_int($v);
    
});

var_dump($v->check('int', "1"));