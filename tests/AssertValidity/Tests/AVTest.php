<?php

use AssertValidity\AV;

/**
 * 
 * @param string $a
 */
function testAV($a)
{
    AV::arg(__METHOD__, func_get_args());
}

class AVTest extends \PHPUnit_Framework_TestCase
{

    public function testAVSuccess()
    {
        testAV("test");
    }
    
    public function testAVFail()
    {
        $this->setExpectedException('\InvalidArgumentException');        
        testAV(1);
    }
    
}
