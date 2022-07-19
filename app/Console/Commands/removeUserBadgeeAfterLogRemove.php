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
use App\Models\Challenge_milestones;

class RemoveUserBadgeeAfterLogRemove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Remove:User:Badgee:AfterLogRemovee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It used to remove user badge after log remove.';

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

    	$challenges = user_challenges::get();
    	$total_rows = $challenges->count();

    	$cron_job_checks = DB::table('cron_job_checks')->select('cron_job_checks.*')
                         ->where([['command_name', '=', 'Remove:User:Badgee:AfterLogRemovee']])
                         ->first();
        $cron_job_checks = json_encode($cron_job_checks);
        $cron_job_checks = json_decode($cron_job_checks, true);
        
        // Set a block size
        $block_size   = $cron_job_checks['block_size'];

        // Init starting offset
        $block_offset = $cron_job_checks['offset'];

        $challenges = user_challenges::skip($block_offset)->take($block_size)->get();
        //$total_rows = $user_challenges->count();
        $challenges = json_encode($challenges);
        $challenges = json_decode($challenges, true);

    	foreach($challenges as $k=>$val){
            $challenge_id = $val['challenge_id'];
            $user_id = $val['user_id'];

            $user_badges = User_badges::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $user_id], ['badge_type', '=', 'challenge_milestone']])->get();
	        $user_badges = json_encode($user_badges);
	        $user_badges = json_decode($user_badges, true);

	       
	//print_r($user_badges); die();
	        $milestone = 0;
	        $milestoneOnSpecificDate = 0;

	        if($user_badges){ 
	            foreach($user_badges as $k => $va){ 

	                $badge_id = $va['badge_id'];
	                $user_id = $va['user_id'];
	                $challenge_id = $va['challenge_id'];

	                if($badge_id){
	                    $ms = Challenge_milestones::where('id', $badge_id)->first();
	                    $ms = json_encode($ms);
	                    $ms = json_decode($ms, true);
	                    if($ms){
	                        $v = $ms;
	                        if($v){
	                            $specific_date_checkbox = $v['specific_date_checkbox'];
	                            $specific_date = $v['specific_date'];
	                         }else{
	                            $specific_date_checkbox = '';
	                            $specific_date = '';
	                         }

	                         $orgDate = $specific_date;

	                         $newDate = '';
	                         if($orgDate){
	                            $tempDate = explode(' ', $orgDate);
	                            $tempDate = explode('-', $tempDate['0']);
	                            $stat = checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
	                            if($stat){

	                               $newDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $orgDate);
	                               //$newDate = date("m/d/Y H:m", strtotime($newDate));
	                            }
	                         }
	                         //echo $specific_date_checkbox .'&&'. $newDate; 
	                         if($specific_date_checkbox && $newDate){
	                            $milestoneOnSpecificDate = $v['milestone_distance'];
	                            $Challenge_infos = new Challenge_infos();
	                            $completePercentageAtSpecificDate = $Challenge_infos->challengeMilestoneCompleteAtSpecificDateBadgeRemoveOnLogRemove($challenge_id, $user_id, $milestoneOnSpecificDate, $newDate, 0);
	                            if($completePercentageAtSpecificDate < 100){
	                                $ChallengeM = User_badges::find($va['id']);
	                                $ChallengeM->delete();
	                            }
	                         }else{
	                            $milestone = $milestone + $v['milestone_distance'];
	                            $Challenge_infos = new Challenge_infos();
	                            $completePercentage = $Challenge_infos->challengeMilestoneCompleteBadgeRemoveOnLogRemove($challenge_id, $user_id, $milestone, 0);
	                            
	                           
	                            
	                            
	                            if($completePercentage < 100){
	                                
	                                $ChallengeM = User_badges::find($va['id']);
	                                $ChallengeM->delete();
	                                                     

	                            }
	                         }

	                    }
	                }

	                
	                
	            }
	        }
    	}

    	// Update block offset, so offset increments by block size (300)  
        $block_offset = $block_offset + $block_size;

          
        $pageData = '';

        if($block_offset >= $total_rows){
            $pageData = array("total" => $total_rows, "offset" => 0);

        }else{
            $pageData = array("total" => $total_rows, "offset" => $block_offset);
        }

        $cron_job_checks = DB::table('cron_job_checks')
                         ->where([['command_name', '=', 'Remove:User:Badgee:AfterLogRemovee']])
                         ->update($pageData);

        \Log::info("It used to remove user badge after log remove.");

    }
}
