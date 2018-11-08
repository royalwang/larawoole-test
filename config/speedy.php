<?php

    return [

        /**
         * -----------------------------------------------------------------------------------------------------------------
         *  Models config
         * -----------------------------------------------------------------------------------------------------------------
         *
         * config your namespace and model's Class name
         *
         */

        'class' => [
            'namespace' => 'App\\Models\\' ,
            'model'     => [
                'role'                        => 'Role' ,
                'user'                        => 'User' ,
                'permission'                  => 'Permission' ,
                'permission_role'             => 'PermissionRole' ,
                'shop'                        => 'Shop' ,
                'equip'                       => 'Equipment' ,
                'area'                        => 'area' ,
                'order'                       => 'Orders' ,
                'sp'                          => 'Sp' ,
                'report'                      => 'Report' ,
                'machine_login_logout_record' => 'MachineLoginLogoutRecord' ,
                'terminal_info'               => 'TerminalInfo' ,
                'teacher'                     => 'teacher' ,
                'city_code'                 =>  'CityCode',
            ] ,
        ] ,

        /**
         * -----------------------------------------------------------------------------------------------------------------
         *  Table config
         * -----------------------------------------------------------------------------------------------------------------
         *
         * config your table name
         *
         */

        'table' => [
            'role'            => 'role' ,
            'permission'      => 'permission' ,
            'user'            => 'users' ,
            'permission_role' => 'permission_role' ,
        ] ,

        /**
         * -----------------------------------------------------------------------------------------------------------------
         *  Menus config
         * -----------------------------------------------------------------------------------------------------------------
         *
         * config your sidebar menu here
         *
         */

        'menus' => [
            'personal'    => [
                'display' => '修改个人信息' ,
                'url'     => '/admin/personal' ,
            ] ,
            'user'        => [
                'display' => '员工账号管理' ,
                'url'     => '/admin/user' ,
            ] ,
            'myreport'    => [
                'display' => '我的工作报告' ,
                'url'     => '/admin/report' ,
            ] ,
            'otherreport' => [
                'display' => '其他工作报告' ,
                'url'     => '/admin/otherreport' ,
            ] ,

            'shop'    => [
                'display' => '门店管理' ,
                'url'     => '/admin/shop' ,
            ] ,
            'work'    => [
                'display' => '门店工作情况' ,
                'url'     => '/admin/work' ,
            ] ,
            'equip'   => [
                'display' => '门店设备管理' ,
                'url'     => '/admin/equip' ,
            ] ,
            'line'    => [
                'display' => '营业报表' ,
                'url'     => '/admin/line' ,
            ] ,
            'area'    => [
                'display' => '区域管理' ,
                'url'     => '/admin/area' ,
            ] ,
            'teacher' => [
                'display' => '导师管理' ,
                'url'     => '/admin/teacher' ,
            ] ,
            'order'   => [
                'display' => '订单管理' ,
                'url'     => '/admin/order' ,
            ] ,
            'sp'      => [
                'display' => '故障审批' ,
                'url'     => '/admin/sp' ,
            ] ,
            'role'    => [
                'display' => '权限管理' ,
                'url'     => '/admin/role' ,
            ] ,

            //        'about' => [
            //            'display' => 'About HanSon',
            //            'sub' => [
            //                'github' => [
            //                    'display' => 'HanSon\'s Github',
            //                    'url' => 'https://github.com/hanson',
            //                    'target' => '_blank'
            //                ],
            //                'blog' => [
            //                    'display' => 'HanSon\'s Blog',
            //                    'url' => 'http://hanc.cc'
            //                ]
            //            ]
            //        ],
            //        'favorite' => [
            //            'display' => 'favorite\'s site',
            //            'sub' => [
            //                'laravel' => [
            //                    'display' => 'Laravel',
            //                    'url' => 'https://laravel.com'
            //                ],
            //                'stackoverflow' => [
            //                    'display' => 'Stackoverflow',
            //                    'url' => 'http://stackoverflow.com'
            //                ]
            //            ]
            //        ]
        ] ,
    ];