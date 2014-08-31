<?php

use AssertValidity\Validatum;

class ValidatumTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Validatum
     */
    protected $validatum = null;


    public function setUp()
    {
        $this->validatum = new Validatum;
    }
    
    public function dataSimpleTrue()
    {
        return [
            ['min:1', 1],
            ['min:1', 2],
            ['min:100', 110],
            ['max:100', 100],
            ['max:0', -1],
            ["range:0:10", 4],
            [["range" => [0, 10]], 5],
            ["int", 4],
            ["numeric", 4.45],
            ["string", "123"],
            ['length_range:5:10', 'abcde'],
            [['array_allowed_keys' => [['a1', 'a2']]], ['a1' => 1, 'a2' => 2]],
            [['array_required_keys' => [['a1', 'a2']]], ['a1' => 1, 'a2' => 2, 'a3' => 1]],
            [['array_allowed_values' => [[1,2,3]]], [1,1,2,3]],
            [['allowed_values' => [['t1', 't2', 't3']]], 't2'],
            
            [['array_keys_rules' => [[
                'type' => ['allowed_values' => [['success', 'failed']] ],
                'code' => ['min:0', 'max:10'],
            ]]], ['type' => 'success', 'code' => 4]],
            
            ['email', 'valid@email.com']
             
        ];
    }
    
    /**
     * 
     * @dataProvider dataSimpleTrue
     */
    public function testSimpleTrue($rule, $value)
    {
        $this->assertTrue($this->validatum->check($rule, $value));
    }
    
    public function dataSimpleFalse()
    {
        return [
            ['min:1', 0],
            ['min:1', -1],
            ['min:100', 99],
            ['min:100', "110"],
            ['max:100', 101],
            ['max:0', 1],
            ['max:100', "100"],
            ["range:0:10", -1],
            [["range" => [-5, 10]], -6],
            ["int", "4"],
            ["numeric", "ffd"],
            ["string", 123],
            ['length_range:5:10', 'abcd'],
            [['array_allowed_keys' => [['a1', 'a2']]], ['a1' => 1, 'a3' => 2]],
            [['array_allowed_keys' => [['a1', 'a2']]], ['a1' => 1, 'a2' => 2, 'a3' => 3]],
            [['array_required_keys' => [['a1', 'a2']]], ['a1' => 1, 'a3' => 2]],
            [['array_allowed_values' => [[1,2,3]]], [1,4,2,3]],
            [['allowed_values' => [['t1', 't2', 't3']]], 't4'],
            
            [['array_keys_rules' => [[
                'type' => ['allowed_values' => [['success', 'failed']] ],
                'code' => ['min:0', 'max:10'],
            ]]], ['type' => 'success', 'code' => 11]],
            
            ['email', 'validate@email.']
        ];
    }
    
    /**
     * 
     * @dataProvider dataSimpleFalse
     */
    public function testSimpleFalse($rule, $value)
    {
        $this->assertFalse($this->validatum->check($rule, $value));
    }
    
    
//    public function testAV()
//    {
//        $this->assertTrue(
//                
//        $this->validatum->check(
//                ['array_keys_rules' => [[
//                    'type' => ['allowed_values' => [['success', 'failed']] ],
//                    //'code' => ['min:0', 'max:10']
//                ]]],
//                ['type' => 'success', 'code' => 4])
//        );
//                
//                
//    }
    
    
}