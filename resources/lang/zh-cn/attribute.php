<?php

    return [
        'role'    => [
            'name'          => '角色名' ,
            'display_name'  => '角色显示名称' ,
            'permission_id' => '权限内容' ,
        ] ,
        'user'    => [
            'name'         => '账号' ,
            'display_name' => '姓名' ,
            'email'        => '邮箱' ,
            'password'     => '密码' ,
            'work_id'      => '工号' ,
            'sex'          => '性别' ,
            'identity'     => '身份证号码' ,
            'hire_date'    => '入职时间' ,
            'shop_id'   =>  '门店',
        ] ,
        'teacher' => [
            'user_id' => '学员' ,
        ] ,
        'area'    => [
            'name' => '区域名称' ,
        ] ,
        'shop'    => [
            'discount' => '门店价格' ,
            'name'     => '门店名称' ,
            'address'  => '门店地址' ,
            'city'     => '所在城市' ,
        ] ,
        'report'  => [
            'content' => '汇报内容' ,
        ] ,
        'equip'   => [
            'type'        => '设备类型' ,
            'name'        => '设备名称' ,
            'verify_code' => '设备识别码' ,
        ] ,
    ];