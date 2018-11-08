<?php

    namespace App\Handlers;

    use Singleton;

    class MonitorHandler
    {
        const PORT = 9510;

        public function __construct()
        {
            //
        }

        /**
         * 监控swoole运行情况
         *
         * @access public
         * @todo $restart_shell 变量需更换成项目脚本地址，此方法未投入使用
         *
         * @since 1.0
         * @return void
         */
        public function monitorSwoole()
        {
            $shell  = "netstat -ntul|grep " . self::PORT . "| grep LISTEN | wc -l";
            $result = shell_exec( $shell );
            if ( $result == 0 )
            {
                //报警，发送短信或者邮箱给负责人
                //nohup命令 以守护进程形式重启
                $restart_shell = "nohup php /var/www/html/swoole_imooc/thinkphp/server/ws.php > /var/www/html/swoole_imooc/thinkphp/server/t.txt &";
                $time          = date( "Y-m-d H:i:s" , time() );
                $date          = date( "Ymd" , time() );
                $file          = "find /var/log/swoole -name {$date}.log | wc -l";
                //记录到日志文件
                if ( $file == 0 )
                {
                    shell_exec( "vim /var/log/swoole/{$date}.log" );
                }
                $log = "echo {$time}.'|restart' >> /var/log/swoole/{$date}.log";
                shell_exec( $log );
                shell_exec( $restart_shell );
            }
            else
            {
                echo date( "Y-m-d H:i:s" , time() ) . 'success';
            }
        }
    }

    //执行一次
    //$monitor = new MonitorHandler();
    $monitor = Singleton::MonitorHandler();
    $monitor->monitorSwoole();

