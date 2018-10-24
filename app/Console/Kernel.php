<?php

    namespace App\Console;

    use App\Console\Commands\SwooleConmmand;
    use App\Console\Commands\AutoLogoutUserMachine;
    use Illuminate\Console\Scheduling\Schedule;
    use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
    use SwooleTW\Http\Commands\HttpServerCommand;
    use App\Console\Commands\ScehduleTask;

    class Kernel extends ConsoleKernel
    {
        /**
         * The Artisan commands provided by your application.
         *
         * @var array
         */
        protected $commands = [
            SwooleConmmand::class , HttpServerCommand::class , ScehduleTask::class , AutoLogoutUserMachine::class ,

        ];

        /**
         * Define the application's command schedule.
         *
         * @param  \Illuminate\Console\Scheduling\Schedule $schedule
         *
         * @return void
         */
        protected function schedule( Schedule $schedule )
        {
            $schedule->command( 'machine:logout' )
                ->hourly();
        }

        /**
         * Register the commands for the application.
         *
         * @return void
         */
        protected function commands()
        {
            $this->load( __DIR__ . '/Commands' );

            require base_path( 'routes/console.php' );
        }
    }
