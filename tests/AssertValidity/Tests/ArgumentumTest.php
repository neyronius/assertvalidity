<?php

use AssertValidity\Argumentum;
use AssertValidity\Validatum;

/**
 * 
 * @return \AssertValidity\Argumentum
 */
function argumentum()
{
    $validatum = new Validatum;
    $argumentum = new Argumentum;
    $argumentum->setValidator($validatum);
    
    $argumentum->getValidator()->import([
        'type1' => ['allowed_values' => [['v1', 'v2', 'v3']]],
        'type2' => ['min' => [0], 'max' => [10]]
    ]);    
    
    return $argumentum;
}

class TestArguments
{
    
    /**
     * Some description
     * 
     * @param int $p1
     * @param string $p2
     */
    public function simpleTypes($p1, $p2)
    {
        argumentum()->check(__METHOD__, func_get_args());
    }
    
    /**
     * Some other description
     * 
     * @param range:0:100 $p1
     * @param min:0 $p2
     * @param min_length:3 $p3
     */
    public function complexRestrictions($p1, $p2, $p3)
    {
        argumentum()->check(__METHOD__, func_get_args());
    }
    
    /**
     * @param type1 $p1
     * @param type2 $p2
     */
    public function customRules($p1, $p2)
    {
        argumentum()->check(__METHOD__, func_get_args());
    }
    
    public function noComments($p1, $p2)
    {
        argumentum()->check(__METHOD__, func_get_args());
    }
}

/**
 * 
 * @param type1 $p1
 * @param email $p2
 * @param range:0:100 $p3
 */
function testArgFunc($p1, $p2, $p3)
{
    argumentum()->check(__FUNCTION__, func_get_args());
}

class ArgumentumTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var TestArguments
     */
    protected $ta = null;
    
    protected function setUp()
    {
        $this->ta = new TestArguments();
    }
    
    public function testSimpleTypesSuccess()
    {
        $this->ta->simpleTypes(1, "1");
    }
    
    public function testSimpleTypesFail()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->ta->simpleTypes(1, 2);
    }
    
    
    public function dataComplexRestrictionsSuccess()
    {
        return [
            [2, 3, "abcde"],
            [100, 0, "abcdedfdfdfdf"],
        ];
    }
    
    /**
     * @dataProvider dataComplexRestrictionsSuccess
     */
    public function testComplexRestrictionsSuccess($p1, $p2, $p3)
    {
        $this->ta->complexRestrictions($p1, $p2, $p3);
    }
     
    public function dataComplexRestrictionsFail()
    {
        return [
            [-1, -1, "a"],
            [-1, 0 , "abcde"],
            [0, 0 , "a2"],
        ];        
    }    

    /**
     * @dataProvider dataComplexRestrictionsFail
     */
    public function testComplexRestrictionsFail($p1, $p2, $p3)
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->ta->complexRestrictions($p1, $p2, $p3);    
    }
    
    public function dataCustomRulesSuccess()
    {
        return [
            ['v1', 0],
            ['v2', 4],
            ['v3', 10]
        ];
    }
    
    /**
     * @dataProvider dataCustomRulesSuccess
     */    
    public function testCustomRulesSuccess($p1, $p2)
    {
        $this->ta->customRules($p1, $p2);
    }
    
    public function dataCustomRulesFail()
    {
        return [
            [null, null],
            [false, false],
            ['', ''],
            ['v4', 10],
            ['v3', 11],
            ['v5', 111],
        ];
    }
    
    /**
     * @dataProvider dataCustomRulesFail
     */
    public function testCustomRulesFail($p1, $p2)
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->ta->customRules($p1, $p2);
    }
    
    public function dataTestArgFuncSuccess()
    {
        return [
            ['v2', 't@t.com', 3],
            ['v3', 'fsfsdfsdf@fddfdf.com', 99]
        ];
    }
    
    /**
     * @dataProvider dataTestArgFuncSuccess
     */
    public function testTestArgFuncSuccess($p1, $p2, $p3)
    {
        testArgFunc($p1, $p2, $p3);
    }
    
    public function dataTestArgFuncFail()
    {
        // * @param type1 $p1
        // * @param email $p2
        // * @param range:0:100 $p3
        
        return [
            ['v5', 't@t.com', 3],
            ['v3', 'fsfsdfsdffddfdf.com', 99],
            ['v3', 'fsfsdfs@dffddfdf.com', 101]
        ];
    }
    
    /**
     * @dataProvider dataTestArgFuncFail
     */
    public function testTestArgFuncFail($p1, $p2, $p3)
    {
        $this->setExpectedException('\InvalidArgumentException');
        testArgFunc($p1, $p2, $p3);
    }
    
    public function testNoComments()
    {
        $this->ta->noComments(1, 3);
    }
    
    
}
