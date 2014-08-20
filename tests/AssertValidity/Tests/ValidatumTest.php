<?php

use AssertValidity\Validatum;

class ValidatumTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * 
     * @return \AssertValidity\Validatum
     */
    protected function prepareValidator()
    {
        $v = new Validatum;
        $v->merge(include __DIR__ . '/../testrules.php');
        return $v;
    }
    
    public function testMerge()
    {
        $v = new Validatum;
        
        $this->assertFalse($v->isRuleSet('test_password'));
        $v->merge(include __DIR__ . '/../testrules.php');
        $this->assertTrue($v->isRuleSet('test_password'));
    }

    public function testSimpleChain()
    {
        $v = $this->prepareValidator();
        
        $this->assertFalse($v->check('test_password', 123456), 'string');
        $this->assertFalse($v->check('test_password', 'abcde'), 'min length');
        $this->assertTrue($v->check('test_password', 'abcdef'), 'success');
        $this->assertFalse($v->check('test_password', 'aaaaabbbbbcccccddddde'), 'max length');
    }
    
    public function testDeepFail()
    {
        $v = $this->prepareValidator();
        $v->check('test_password', 123456);
        $this->assertEquals(['test_password', 'string'], $v->getErrorBackTrace());
    }
    
    public function testMin()
    {
        $v = $this->prepareValidator();
        $v->check('min:2', 1);
        
        $this->assertEquals(['min'], $v->getErrorBackTrace());
    }
    
    public function testAllowedKeys()
    {
        $v = $this->prepareValidator();
        
        $rule = ['array_allowed_keys', ['a', 'b', 'c']];
        
        $this->assertTrue($v->check($rule, ['a' => 1, 'b' => 3]));
        $this->assertFalse($v->check($rule, ['a' => 1, 'b' => 3, 'd' => 5]));
    }
    
    public function testAllowedValues()
    {
        $v = $this->prepareValidator();
        
        $this->assertTrue($v->check('test_array_types', ['test1', 'test3']), 'true');
        $this->assertFalse($v->check('test_array_types', ['not_allowed_value', 'test2']), 'false');
        
    }
    
    public function tesCustomtHash()
    {
        $v = $this->prepareValidator();
        
        $this->assertTrue($v->check('test_custom_hash', ['status' => true, 'message' => 'ok']));
        $this->assertFalse($v->check('test_custom_hash', ['status' => 'true', 'message' => 1]));
        
    }
}
