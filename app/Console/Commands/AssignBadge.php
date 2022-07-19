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

class AssignBadge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Assign:Badge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It used to assign badge for member_since badge_type.';

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

        $badges = Badges::where("badge_type", 'member_since')->get();
        $badges = json_encode($badges);
        $badges = json_decode($badges, true);
        foreach($badges as $key => $value){ 
            $badge_id = $value['id'];
            $badge_type = $value['badge_type'];
            $badge_condition = $value['badge_condition'];
            $condition_limit = $value['condition_limit'];

            $user = User::where("user_type", 2)->get();
            $user = json_encode($user);
            $user = json_decode($user, true);

            foreach($user as $key => $val){
                $challengeInfo = new Challenge_infos();
                $created_at = $val['created_at'];
                $user_id = $val['id'];
                if($badge_condition == 'month'){
                    $calculateMonth = $challengeInfo->calculateMonth($created_at);
                    if($calculateMonth >= $condition_limit){
                        $challengeInfo->assignBadgeForMemberSince($badge_id, $user_id, $badge_type);
                        
                    }

                }elseif($badge_condition == 'year'){
                    $calculateYear = $challengeInfo->calculateYear($created_at);
                    if($calculateYear >= $condition_limit){
                        $challengeInfo->assignBadgeForMemberSince($badge_id, $user_id, $badge_type);
                        
                    }

                }

            }


        }

        //\Log::info("It is use to assign badge for member_since badge_type!");

    }
}
