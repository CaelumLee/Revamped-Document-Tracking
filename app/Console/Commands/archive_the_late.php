<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Holidays;
use App\User;
use App\Docu;

class archive_the_late extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'script:to_archives';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All the records that are not accepted and passed due final 
                                action date will be archived';

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
        $docus_to_archive = Docu::where([
            ['final_action_date', '<', Carbon::now()],
            ['deleted_at', null]
        ])
        ->get();

        $count_cancelled;
        $count_approved;
        
        if($docus_to_archive->isNotEmpty()){
            foreach($docus_to_archive as $docu){
                if($docu->statuscode_id != 1){
                    $docu->statuscode_id = 4;
                    $docu->save();
                    $count_cancelled++;
                }
                else{
                    $count_approved++;
                }
                $docu->delete();
            }
            $this->info('All ' . $docus_to_archive->count() . ' has beed archived\n' . 
                        $count_cancelled . ' of which are cancelled and ' . $count_approved .
                        'are already approved');
        }
        else{
            $this->error('No records found to work at');
        }
        
    }
}
