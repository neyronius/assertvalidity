<?php

return [
    
    'int' => function($v){
        return is_int($v);
    },
            
    'min' => [function($v, $min){ return $v >= $min[0];}],
    'max' => [function($v, $max){ return $v <= $max[0];}],
    'range' => ['int', function($v, $m){ return $v <= $m[1] && $v >= $m[0];}],
            
    'numeric' => function($v){ return is_numeric($v);},
    'float' => function($v){return is_float($v);},
            
    'string' => function($v){
        return is_string($v);
    },
    
    'boolean' => function($v){
        return is_bool($v);
    },
            
    'is_array' => function($v){
        return is_array($v);
    },
            
    'array_allowed_keys' => function($v, $allowedKeys){
        return count(array_diff_key($v, array_flip($allowedKeys))) === 0;
    },
            
    'array_required_keys' => function($v, $requiredKeys){
        
        foreach ($requiredKeys as $k){
            if(!isset($v[$k])){
                return false;
            }
        }
        
        return true;
    },
            
    'array_allowed_values' => function($v, $allowedValues){
        return count(array_diff($v, $allowedValues)) === 0;
    },
            
    'array_keys_rules' => function($v, $rules, \AssertValidity\Validatum $validatum){
        foreach ($v as $key => $value){
            if(isset($rules[$key])){
                if(!$validatum->checkWorker([$rules[$key], null], $value)){
                    return false;
                }
            }
        }
        
        return true;
    },
            
    'min_length' => function($v, $l){
        return strlen($v) >= $l;
    },

    'max_length' => function($v, $l){
        return strlen($v) <= $l;
    },
            
    'str_length' => function($v, $l){ return strlen($v) === $l;},
            
    'values' => function($v, $options){
        return in_array($v, $options, true);
    }
    
];
