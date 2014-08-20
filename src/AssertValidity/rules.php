<?php

return [
    
    'int' => function($v){
        return is_int($v);
    },
            
    'min' => ['int', function($v, $min){ return $v >= $min;}],
    'max' => ['int', function($v, $max){ return $v <= $max;}],
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
                //@error This call clear backtrace history
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
            
    //'ino_username' => ['string', 'minLength:8', 'maxLength:30'],
            
//    'userarray' => [ 'hash' => [
//        
//            'allowed_keys' => ['id', 'first_name', 'last_name', 'deleted', 'created', 'email'],
//            'required_keys' => ['id', 'email'],
//            'disallowed_keys' => ['imail'],
//            'elements' => [
//                'id' => ['int', 'min:0', 'max:1000'],
//                'email' => ['email'],
//                'first_name' => ['string', 'minLength:1', 'maxLength:100'],
//            ]
//        ]
//    ]
    
];
