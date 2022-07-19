<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function show($id)
    {
        $badge_array = [];

        // if(!Auth::check()) {
        //     return redirect()->route('frontend.home');
        // }

        if(request()->get('fbclid')) {
            return redirect('https://www.challengeinmotion.com');
        }

        $badge = DB::table('badges')->where('id', $id)->first();
        if($badge) {
            $badge_array = [
                "id" => $badge->id,
                "url" => route("test.show", $badge->id),
                "type" => $badge->badge_type,
                "title" => $badge->badge_name,
                "description" => "#ChallengeInMotion",
                "image" => !empty($badge->badge_logo) ? asset($badge->badge_logo) : asset("assets/images/Header.jpg"),
                "site" => "ChallengeInMotion",
                "creator" => Auth::user()->name ?? "ChallengeInMotion",
            ];
        }

        $arr['badge'] = $badge_array;

        return view('test',$arr);
    }

    public function view($id)
    {
        $array = [];

        // if(!Auth::check()) {
        //     return redirect()->route('frontend.home');
        // }

        if(request()->get('fbclid')) {
            return redirect('https://www.challengeinmotion.com');
        }

        $challenge_milestone = DB::table('challenge_milestones')->where('id', $id)->first();
        if($challenge_milestone) {
            $array = [
                "id" => $challenge_milestone->id,
                "url" => route("test.view", $challenge_milestone->id),
                "type" => $challenge_milestone->milestone_type,
                "title" => $challenge_milestone->milestone_name,
                "description" => "#ChallengeInMotion",
                "image" => !empty($challenge_milestone->milestone_pic) ? asset($challenge_milestone->milestone_pic) : asset("assets/images/Header.jpg"),
                "site" => "ChallengeInMotion",
                "creator" => Auth::user()->name ?? "ChallengeInMotion",
            ];
        }

        $arr['badge'] = $array;

        return view('test',$arr);
    }
}
