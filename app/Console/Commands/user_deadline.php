<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Holidays;
use App\User;
use App\Docu;
use App\Transaction;
use App\Notifications\DeadlineNotif;

class user_deadline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'script:create_notif_deadline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate notifications to all users who has deadlines to meet';

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
        $date_now = Carbon::now();
        $date_tom = Carbon::now()->addWeekdays(1);

        $holiday_dates = Holidays::pluck('holiday_date')->toArray();

        CarbonPeriod::macro('countDaysLeft', function() use ($holiday_dates){
            $add_more = 0;
            $range = $this->filter('isWeekday')->toArray();    
            foreach($range as $date){
                $in = in_array($date->format('m-d'), $holiday_dates);
                if($in){
                    $add_more += 1;
                }
            }
            return $add_more;
        });

        $add_buffer = CarbonPeriod::create($date_now->format('Y-m-d'), $date_tom->format('Y-m-d'))->countDaysLeft();
        $date_time_selector = Carbon::createFromFormat('Y-m-d H:i', $date_tom->addWeekday($add_buffer)
            ->format('Y-m-d') . '23:59')
            ->toDateTimeString();

        $transactions = Transaction::where('date_deadline', $date_time_selector)
        ->where(function($query){
            $query->where('is_received', 0)
                ->orWhere([
                    ['to_continue', 1],
                    ['has_sent', 0]
                ]);
        })
        ->get();
        
        if($transactions->isNotEmpty()){
            foreach($transactions as $t){
                $docu = Docu::find($t->docu_id);
                $user = User::find($t->recipient);
    
                $this->info('Sending notif to ' . $user->username . ' for Document Record ' . 
                $docu->reference_number);
                $user->notify(new DeadlineNotif($docu));
                $this->info('Notification sent to ' . $user->username);
            }
        }
        else{
            $this->error('No pending deadlines found');
        }
    }
}
