<?php

use AssertValidity\AV;


/**
 * Function for test
 * 
 * @param userid $userId
 * @param price $price
 * @param tf_options $options
 */
function __testFunction($userId, $price, $options)
{
    AV::arg(__FUNCTION__, func_get_args());
}

class AVTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        AV::getValidatum()->import([
            'userid' => ['int', 'min:1'],
            'price' => ['float', 'min' => 0.01, 'max' => 1000],
            'tf_options' => [
                    'is_array', 
                    'array_required_keys' => ['order', 'allow'],
                    'array_allowed_keys' => ['order', 'allow'],
                    'array_keys_rules' => [
                        'order' => ['values' => ['asc', 'desc']],
                        'allow' => 'boolean'
                    ]
            ]
        ]);        
    }
    
    public function testUserId()
    {
        //$this->assertTrue(AV::rule('min:0.', 10.0));
    }
    
    
    public function testFunction()
    {
        try{
            __testFunction(3, 10.00, ['order' => 'asc', 'allow' => false]);
            $this->assertTrue(true);
        } catch (InvalidArgumentException $ex) {
            $this->assertTrue(false, $ex->getMessage());
        }
        
        try{
            __testFunction(3, 10.00, ['order' => 'asc', 'allow' => 1]);
            $this->assertTrue(false);
        } catch (InvalidArgumentException $ex) {
            $this->assertTrue(true);
        }
    }
    
    
}