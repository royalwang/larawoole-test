<?php

    return [
        'admin' => [
            'user'    => [
                'update' => [
                    'name'      => 'required|max:255' ,
                    'sex'       => 'required' ,
                    'identity'  => 'required' ,
                    'hire_date' => 'required' ,
                    //                'email' => 'required|email|max:255|unique:users,email',
                    //                'password' => 'nullable|min:6',
                ] ,
                'store'  => [
                    'name'         => 'required|max:255' ,
                    //'work_id'      => 'required|unique:users,work_id' ,
                    //                'email' => 'required|email|max:255|unique:users',
                    'sex'          => 'required' ,
                    'identity'     => 'required' ,
                    'password'     => 'required' ,
                    'display_name' => 'required' ,
                    'hire_date'    => 'required' ,
                    'shop_id'   => 'required',
                ] ,
            ] ,
            'role'    => [
                'update' => [
                    'name'          => 'required|max:255|unique:role,name' ,
                    'display_name'  => 'required|max:255' ,
                    'permission_id' => 'required' ,
                ] ,
                'store'  => [
                    'name'          => 'required|max:255|unique:role,name' ,
                    'display_name'  => 'required|max:255' ,
                    'permission_id' => 'required' ,
                ] ,
            ] ,
            'shop'    => [
                'update' => [
                    'name'     => 'required|max:255' ,
                    'address'  => 'required|max:255' ,
                    'discount' => 'required|numeric' ,
                ] ,
                'store'  => [
                    'name'     => 'required|max:255' ,
                    'address'  => 'required|max:255' ,
                    'discount' => 'required|numeric' ,
                    'city'     => 'required' ,
                ] ,
            ] ,
            'equip'   => [
                'update' => [
                    'name'        => 'required|max:255' ,
                    'verify_code' => 'required' ,
                ] ,
                'store'  => [
                    'name'        => 'required|max:255' ,
                    'type'        => 'required' ,
                    'verify_code' => 'required' ,
                ] ,
            ] ,
            'order'   => [
                'update' => [
                    'name' => 'required|max:255' ,
                    'type' => 'required|max:255' ,
                ] ,
                'store'  => [
                    'name' => 'required|max:255' ,
                    'type' => 'required|max:255' ,
                ] ,
            ] ,
            'area'    => [
                'update' => [
                    'name' => 'required|max:255' ,
                ] ,
                'store'  => [
                    'name' => 'required|max:255' ,
                ] ,
            ] ,
            'sp'      => [
                'update' => [
                ] ,
                'store'  => [
                ] ,
            ] ,
            'teacher' => [
                'update' => [
                    'user_id' => 'required | max:5' ,
                ] ,
            ] ,
            'report'  => [
                'store' => [
                    'content' => 'required' ,
                ] ,
            ] ,
        ] ,
    ];