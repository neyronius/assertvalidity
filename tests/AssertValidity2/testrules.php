<?php

// 'length_range' => [1, 6] => ['length_range' => [1, 6]]

return [
    
    'test_password' => ['string', 'length_range' => [1, 6], 'max_length' => [20]],
    
    'test_hash_request' => [
                    'is_array', 
                    'array_allowed_keys' => [['username', 'email', 'active']],
                    ['array_allowed_keys', [['username', 'email', 'active']]]
    ],
    
    'test_array_types' => [
        'is_array',
        'array_allowed_values' => ['test1', 'test2', 'test3']
    ],
    
    'test_custom_hash' => [
        'is_array',
        'array_required_keys' => ['status', 'message'],
        
        'array_keys_rules' => [
            'status' => 'boolean',
            'message' => ['string', 'min_length' => 1]
        ]
        
        
        
    ]
    
];

