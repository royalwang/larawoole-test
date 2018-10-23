<?php

    return [
        'admin' => [
            'public'   => [
                'submit'     => '提交' ,
                'detail'     => '查看' ,
                'action'     => '操作' ,
                'edit'       => '编辑' ,
                'create'     => '创建' ,
                'destroy'    => '删除' ,
                'none'       => '无' ,
                'created_at' => '创建时间' ,
                'close'      => '关闭' ,
                403          => '您没有权限操作此模块' ,
            ] ,
            'user'     => [
                'title'              => '用户' ,
                'work_id'            => '工号' ,
                'name'               => '账号' ,
                'display_name'       => '姓名' ,
                'identity'           => '身份证' ,
                'sex'                => '性别' ,
                'email'              => '邮箱' ,
                'password'           => '密码' ,
                'role'               => '角色' ,
                'shop'               => '门店' ,
                'equip'              => '设备' ,
                'create_user_failed' => '创建用户失败！' ,
                'edit_user_failed'   => '编辑用户失败！' ,
                'sure_to_delete'     => '确定删除该用户？' ,
                'delete_user_failed' => '删除用户失败！' ,
            ] ,
            'role'     => [
                'title'              => '角色' ,
                'name'               => '角色名' ,
                'display_name'       => '角色显示名称' ,
                'permission'         => '权限' ,
                'create_role_failed' => '创建用户失败' ,
                'edit_role_failed'   => '编辑用户失败！' ,
                'sure_to_delete'     => '确定删除该用户？' ,
                'delete_role_failed' => '删除用户失败！' ,
            ] ,
            'personal' => [
                'edit' => '修改密码' ,
            ] ,
            'shop'     => [
                'title'              => '店铺' ,
                'name'               => '店铺名称' ,
                'manager'            => '店长名称' ,
                'manager_id'         => '店长' ,
                'area'               => '店铺区域' ,
                'address'            => '店铺地址' ,
                'establish_time'     => '建店时间' ,
                'edit'               => '修改' ,
                'sure_to_delete'     => '确定删除该门店？' ,
                'update'             => '更新' ,
                'create_shop_failed' => '创建门店失败！' ,
                'edit_shop_failed'   => '修改门店失败' ,
                'delete_shop_failed' => '删除门店失败' ,
                'discount'           => '门店收费' ,
            ] ,
            'area'     => [
                'title'              => '区域' ,
                'name'               => '区域名称' ,
                'manager'            => '区域经理' ,
                'user_id'            => '区域经理' ,
                'shop'               => '区域门店' ,
                'shop_num'           => '区域门店数' ,
                'edit'               => '修改' ,
                'sure_to_delete'     => '确定删除该区域？' ,
                'update'             => '更新' ,
                'create_area_failed' => '创建区域失败！' ,
                'edit_area_failed'   => '修改区域失败' ,
                'delete_area_failed' => '删除区域失败' ,
            ] ,
            'equip'    => [
                'title'              => '设备' ,
                'name'               => '设备名称' ,
                'type'               => '设备类型' ,
                'user_id'            => '使用人' ,
                'money'              => '收钱终端' ,
                'logout'             => '正常' ,
                'holdon'             => '挂起' ,
                'error'              => '故障' ,
                'staff'              => '员工机' ,
                'uuid'               => '识别码' ,
                'status'             => '状态' ,
                'shop'               => '所属门店' ,
                'edit'               => '修改' ,
                'sure_to_delete'     => '确定删除该设备？' ,
                'update'             => '更新' ,
                'create_area_failed' => '创建设备失败！' ,
                'edit_area_failed'   => '修改设备失败' ,
                'delete_area_failed' => '删除设备失败' ,
            ] ,
            'sp'       => [
                'title'          => '故障审批' ,
                'name'           => '故障名称' ,
                'type'           => '审批类型' ,
                'user_id'        => '审批人' ,
                'bz_id'          => '报障人' ,
                'status'         => '审批状态' ,
                'result'         => '结果' ,
                'detail'         => '审批详情' ,
                'shop'           => '所属门店' ,
                'goto'           => '查看' ,
                'updated_at'     => '更新时间' ,
                'num'            => '未回复问题/总数' ,
                'edit'           => '修改' ,
                'update'         => '更新' ,
                'edit_sp_failed' => '审批失败' ,
                'sure_to_submit' => '确认提交' ,
                'confirm'        => '确认' ,
            ] ,
            'line'     => [
                'title'      => '营业情况' ,
                'shop'       => '门店名称' ,
                'manager'    => '店长' ,
                'staff'      => '门店总员工数' ,
                'staff_name' => '员工姓名' ,
                'role'       => '职位' ,
                'efficiency' => '平均效率（s）' ,
                'work_staff' => '在线员工数' ,
                'waiting'    => '等候人数' ,
                'hint'       => '指示灯' ,
                'red'        => '红灯' ,
                'green'      => '绿灯' ,
                'yellow'     => '黄灯' ,
                'time'       => '查询' ,
                'order_num'  => '总处理订单数' ,
                'profit'     => '统计营业额' ,
                'start_time' => '开始时间' ,
                'end time'   => '结束时间' ,
                'goto'       => '查看' ,
            ] ,
            'order'    => [
                'title'    => '订单管理' ,
                'num'      => '订单号' ,
                'get_type' => '取票方式' ,
                'wechat'   => '微信' ,
                'alipay'   => '支付宝' ,
                'noget'    => '未取票' ,
                'hasget'   => '已取票' ,
                'pay_type' => '支付方式' ,
                'pay_time' => '支付时间' ,
                'shop'     => '门店' ,
                'goto'     => '查看' ,
                'pic'      => '用户头像' ,
                'name'     => '用户昵称' ,
                'if_get'   => '是否取票' ,
                'get_time' => '取票时间' ,
                'user'     => '接单员工' ,
                'finish'   => '是否完成' ,
                'undo'     => '未处理' ,
                'todo'     => '正在处理' ,
                'done'     => '完成' ,
                'wait_num' => '排队号' ,
                'status'   => '支付状态' ,
                'price'    => '支付价格' ,
                'sex'      => '性别' ,
                'age'      => '年龄' ,
            ] ,
            'work'     => [
                'title'           => '工作管理' ,
                'staff_num'       => '总员工人数' ,
                'today_order_num' => '今天已处理订单' ,
                'total_order_num' => '总处理订单数' ,
                'manager'         => '店长' ,
                'staff_name'      => '员工姓名' ,
                'status'          => '状态' ,
                'order_num'       => '订单号' ,
                'doing_order_num' => '正在处理订单号' ,
                'month_order_num' => '当月处理订单数' ,
                'day'             => '当月上班天数' ,
                'shop'            => '所属门店' ,
                'goto'            => '查看' ,
                'hangon'          => '挂起' ,
                'onwork'          => '正在工作' ,
                'logout'          => '登出' ,
                'today_staff'     => '上班员工人数' ,
                'waiting'         => '等候客户数' ,
                'hint'            => '指示灯' ,
                'red'             => '红灯' ,
                'green'           => '绿灯' ,
                'yellow'          => '黄灯' ,
                'profit'          => '当天收益' ,
            ] ,
            'teacher'  => [
                'display_name'        => '导师名称' ,
                'under_sum'           => '指导人数' ,
                'title'               => '导师' ,
                'manager'             => '指导店长' ,
                'user_id'             => '学员人数' ,
                'edit'                => '调整指导学员' ,
                'edit_teacher_failed' => '编辑导师学员失败！' ,
            ] ,
            'report'   => [
                'title'                => '工作汇报' ,
                'create'               => '写汇报' ,
                'view'                 => '查看' ,
                'shop'                 => '所属门店' ,
                'job'                  => '职位' ,
                'name'                 => '姓名' ,
                'time'                 => '汇报时间' ,
                'content'              => '汇报内容' ,
                'create_report_failed' => '上传汇报失败!' ,
            ] ,
        ] ,
        'index' => [
            'AccountName'          => '账号名称' ,
            'Login'                => '登录' ,
            'Logout'               => '登出' ,
            'Password'             => '密码' ,
            'Remember Me'          => '记住我' ,
            'Forget Your Password' => '忘记密码' ,
        ] ,

    ];