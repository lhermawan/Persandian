<?php

return [

    'pagination' => [
        'limit' => 10,
    ],
    
    'value' => [
        'exist' => 1,
        'zero' => 0,
    ],

    'status' => [
        'notactive' => 0,
        'active' => 1,
    ],

    'url' => [
        'crash' => "#",
    ],

    'pass' => [
        'regex' => "regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/",
    ],

    'path' => [
        'profile' => "/upload/profile/",
    ]

];
