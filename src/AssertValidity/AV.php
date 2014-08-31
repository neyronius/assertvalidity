<?php

namespace AssertValidity;

class AV
{
    
    /**
     *
     * @var Validatum => VAlidatum
     */
    protected static $validatum = null;
    
    /**
     *
     * @var Argumentum
     */
    protected static $argumentum = null;

    /**
     * 
     * @return Validatum
     */
    public static function getValidatum()
    {
        if(self::$validatum === null){
            self::$validatum = new Validatum;
        }
        
        return self::$validatum;
    }
    
    /**
     * 
     * @return Argumentum
     */
    public static function getArgumentum()
    {
        if(self::$argumentum === null){
            self::$argumentum = new Argumentum;
            
            self::$argumentum->setValidator(self::getValidatum());
        }
        
        return self::$argumentum;        
    }



    public static function rule($rule, $value)
    {
        return self::getValidatum()->check($rule, $value);
    }
    
    public static function arg($method, $arguments)
    {
        return self::getArgumentum()->check($method, $arguments);
    }
    
    /**
     * Validate hash array against rule
     * Validator will perform full validation process
     * 
     * @param rule $rule array or string
     * @param hash $value
     */
    public static function hash($rule, $value)
    {
        if(is_string($rule)){
            $ruleDescription = self::getValidatum()->getRuleDescription($rule);
            
            if(!$ruleDescription){
                throw new \RuntimeException("Can't find a description for rule $rule");
            }
            
        }elseif(is_callable($rule) || is_array($rule) ){
            $ruleDescription = $rule;
        }else{
            throw new \InvalidArgumentException('Parameter $rule has invalid type');
        }
        
        if(is_callable($ruleDescription)){
            throw new \RuntimeException('AV::hash method does not accept callable as rule description');
        }
        
//        if(isset($ruleDescription['array_allowed_values'])){
//            self::getValidatum()->check(['array_allowed_values', ], $value)
//        }
        
    }
}
