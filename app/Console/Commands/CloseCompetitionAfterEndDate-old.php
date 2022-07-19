<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User_challenges;
use App\Models\Badges;
use App\Models\User_badges;
use Carbon\Carbon;
use App\User;
use App\Models\User_infos;
use App\Models\Challenges;
use App\Models\Challenge_infos;

class CloseCompetitionAfterEndDateOld extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Close:Competition:AfterEndDate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It used to close competition after end date.';

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
     * @return int
     */
    public function handle()
    {

        $challenges = Challenges::get();
        $challenges = json_encode($challenges);
        $challenges = json_decode($challenges, true);
        foreach($challenges as $key => $value){
            $badge_id = $value['id'];
            $event_end_date = $value['event_end_date'];
            $newDate = '';
            if($event_end_date){
            $tempDate = explode(' ', $event_end_date);
              $tempDate = explode('-', $tempDate['0']);
              $stat = checkdate($tempDate[1], $tempDate[2], (int)$tempDate[0]);
              if($stat){
                if($event_end_date < Carbon::now('UTC')->toDateTimeString()){

                    $challenge = Challenges::where("id", $badge_id)->first();
                    $challenge->status = 2;
                    $challenge->update();

                    $User_challenges = DB::table('user_challenges')->where('challenge_id', $badge_id)->update(['status' => 2]);


                }elseif($event_end_date > Carbon::now('UTC')->toDateTimeString()){

                    $challenge = Challenges::where("id", $badge_id)->first();
                    $challenge->status = 1;
                    $challenge->update();

                    $User_challenges = DB::table('user_challenges')->where('challenge_id', $badge_id)->update(['status' => 1]);


                }
              }
            }
        }

        \Log::info("It used to close competition after end date.");

    }
}
