<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Challenges extends Model
{
    public function infos()
    {
        return $this->hasMany(Challenge_infos::class, 'challenge_id' , 'id');
    }

    public function logs()
    {
        return $this->hasMany(Challenge_logs::class, 'participation_id' , 'id');

    }

    public function challenges()
    {
        return $this->belongsToMany(
            User::class,
            'user_challenges',
            'challenge_id',
            'user_id');
    }

}
