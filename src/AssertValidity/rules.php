<?php

return [
    'int' => function($v){return is_int($v);},
    'min' => ['int', function($v, $min){ return $v >= $min;}],
    'max' => ['int', function($v, $max){ return $v <= $max;}],
    'range' => ['int', function($v, $min, $max){ return $v <= $max && $v >= $min;}],
            
    'numeric' => function($v){ return is_numeric($v);},
    'float' => function($v){return is_float($v);},
            
    'string' => function($v){return is_string($v);},
    'length' => function($v, $l){ return strlen($v) === $l;},
    'min_length' => function($v, $l){return strlen($v) >= $l;},
    'max_length' => function($v, $l){return strlen($v) <= $l;},
    'length_range' => function($v, $min, $max){ return strlen($v) >= $min && strlen($v) <= $max;},

    'boolean' => function($v){return is_bool($v);},
    'array' => function($v){return is_array($v);},
            
    'email' => function($v){return filter_var($v, FILTER_VALIDATE_EMAIL) === $v;},
            
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
                if(!$validatum->checkWorker($rules[$key], $value)){
                    return false;
                }
            }
        }
        
        return true;
    },
            
    'allowed_values' => function($v, $options){
        return in_array($v, $options, true);
    },
];