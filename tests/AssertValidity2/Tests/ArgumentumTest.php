<?php

if(!function_exists('validity')){
    
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
    
}else{
    die("Can't exexute tests: function validity is already defined");
}

if(!class_exists('ClassForArgumentumCheck')){
    
    class ClassForArgumentumCheck
    {
        
        public function __construct()
        {
            
        }
        
        /**
         * Some description
         * 
         * @param int $arg1
         * @param some_allowed_values $arg2
         * @param special_hash $arg3
         */
        public function simpleDescription($arg1, $arg2, $arg3)
        {
            validity()->check(__METHOD__, func_get_args());
        }
    }
}

class ArgumentumTest extends \PHPUnit_Framework_TestCase
{

    public function testCommon()
    {
        /**
         * @param int $a
         * @param string $b
         * @param range:0:10 $c
         */
        function common($a, $b, $c)
        {
            validity()->check(__FUNCTION__, func_get_args());
            return true;
        }
        
        $this->assertTrue(common(1, "2", 3));
        
        $result = 1;
        
        try{
            common(1, "234", 11);
            $result = 2;
        } catch (InvalidArgumentException $ex) {
            $result = 3;
        }
        
        $this->assertEquals(3, $result);
    }
    
//    public function testObjectMethod()
//    {
//        $c = new ClassForArgumentumCheck();
//        
//        validity()->getValidator()->add("some_allowed_values", function($v){
//            return in_array($v, ['allowed_value', 'allowed_value_2'], true);
//        });
//        
//        validity()->getValidator()->add("special_hash", [
//                    'is_array',
//                    'array_allowed_keys' => ['status', 'message'],
//                    
//        ]);
//        
//        
//        $testStatus = null;
//        try {
//            $c->simpleDescription(
//                        1, 
//                        "allowed_value", 
//                        ['status' => true, 'message' => 'Description']
//            );
//            $testStatus = 1;
//        } catch (InvalidArgumentException $ex) {
//            $testStatus = 2;
//        }
//        
//        $this->assertEquals(1, $testStatus, "Positive test");
//    }
    
    
}


