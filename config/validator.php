<?php

return [
    'admin' => [
        'user' => [
            'update' => [
                'name' => 'required|max:255',
//                'email' => 'required|email|max:255|unique:users,email',
//                'password' => 'nullable|min:6',
            ],
            'store' => [
                'name' => 'required|max:255',
                'work_id' => 'required|max:128|unique:users,work_id',
//                'email' => 'required|email|max:255|unique:users',
                'password' => 'min:6',
            ],
        ],
        'role' => [
            'update' => [
                'name' => 'required|max:255|unique:role,name',
                'display_name' => 'required|max:255',
            ],
            'store' => [
                'name' => 'required|max:255|unique:role,name',
                'display_name' => 'required|max:255',
            ],
        ],
        'shop' => [
            'update' => [
                'name' => 'required|max:255',
                'address' => 'required|max:255',
                'discount' => 'required|numeric'
            ],
            'store' => [
                'name' => 'required|max:255',
                'address' => 'required|max:255',
                'discount' => 'required|numeric'
            ],
        ],
        'equip' => [
            'update' => [
                'name' => 'required|max:255'
            ],
            'store' => [
                'name' => 'required|max:255',
                'type' => 'required|max:255',
            ],
        ],
        'order' => [
            'update' => [
                'name' => 'required|max:255',
                'type' => 'required|max:255',
            ],
            'store' => [
                'name' => 'required|max:255',
                'type' => 'required|max:255',
            ],
        ],
        'area' => [
            'update' => [
                'name' => 'required|max:255',
            ],
            'store' => [
                'name' => 'required|max:255',
            ],
        ],
        'sp' => [
            'update' => [
            ],
            'store' => [
            ],
        ],
        'teacher' => [
            'update' =>[
                'user_id' => 'required | max:5',
            ]
        ],
        'report' => [
            'store' => [
                'content' => 'required',
            ]
        ]
    ],
];