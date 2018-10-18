<?php

    namespace App\Console\Commands;

    use Illuminate\Console\Command;

    class SwooleConmmand extends Command
    {
        protected $serv;
        /**
         * The name and signature of the console command.s
         *
         * @var string
         */
        protected $signature = 'swoole {action}';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'swoole control commands';

        /**
         * Create a new command instance.
         *
         * @return void
         */
        public function __construct()
        {
            parent::__construct();
        }

        /**
         * Execute the console command.
         *
         * @return mixed
         */
        public function handle()
        {
            $arg = $this->argument('action');
            switch ($arg) {
                case 'start':
                    $this->start();
                    break;
                case 'reload':
                    $this->reload();
                    break;
                case 'shutdown':
                    $this->shutdown();
                    break;
                case 'connection_list':
                    $this->connection_list();
                    break;
                case 'get_local_ip':
                    $this->get_local_ip();
                    break;
            }
        }

        private function start()
        {
            $this->serv = new \swoole_server('0.0.0.0' , 9510,SWOOLE_PROCESS,SWOOLE_SOCK_TCP);
            $this->serv->set([
                'worker_num'    => 6 ,
                'max_request'   => 1000 ,
                'dispatch_mode' => 2 ,
                'debug_mode'    => 1 ,
                'daemonize'     => true ,
                'log_file' => storage_path('logs/xingjian.log'),
                'heartbeat_idle_time' => 60,
                'heartbeat_check_interval' => 30,
            ]);
            $handlers = \App::make(\App\Handlers\SwooleHandler::class);

            $this->serv->on('start' , [$handlers,'onStart']);
            $this->serv->on('connect' , [$handlers,'onConnect']);
            $this->serv->on('receive' , [$handlers,'onReceive']);
            $this->serv->on('close' , [$handlers,'onClose']);

            $this->serv->start();
        }

        private function reload()
        {
            $this->serv->reload();
            $this->info('swoole restart worker process');
        }

        private function shutdown()
        {
            $this->serv->shutdown();
            $this->info('swoole observer shutdown');
        }

        private function connection_list()
        {
            $start_fd = 0;
            while (true) {
                $conn_list = $this->serv->connection_list($start_fd , 10);
                if ($conn_list === false) {
                    echo "finish\n";
                    break;
                }
                $start_fd = end($conn_list);
                var_dump($conn_list);
                foreach ($conn_list as $fd) {
                    $this->serv->send($fd , "broadcast");
                }
            }
            $this->info('swoole observer get connection list');
        }

        private function get_local_ip()
        {
            var_dump(swoole_get_local_ip());
            $this->info('swoole observer get local ip');
        }

    }
