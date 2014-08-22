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
}
