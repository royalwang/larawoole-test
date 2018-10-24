<?php

    namespace App\Console\Commands;

    use Carbon\Carbon;
    use Illuminate\Console\Command;
    use Illuminate\Support\Facades\Log;
    use Speedy;

    class AutoLogoutUserMachine extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'machine:logout';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Command description';

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
            $equip = Speedy::getModelInstance( 'equip' )
                ->where( 'valid' , '1' )
                ->where( 'user_id' , '<>' , 'null' )
                ->get();

            if ( isset( $equip ) )
            {
                foreach ( $equip as $v )
                {
                    Speedy::getModelInstance( 'machine_login_logout_record' )->create(
                        [
                            'user_id'     => $v->user_id ,
                            'verify_code' => $v->verify_code ,
                            'type'        => '1' ,
                            'status'      => '1' ,
                        ]
                    );
                    Speedy::getModelInstance( 'equip' )->where( 'id' , $v->id )->update(
                        [
                            'user_id' => null ,
                            'status'  => '2' ,
                        ]
                    );
                    Log::info( 'Machine ' . $v->id . ' Logout : ' . Carbon::now() );
                }
            }
            else
            {
                Log::info( 'No Machine Logout : ' . Carbon::now() );
            }
        }
    }
