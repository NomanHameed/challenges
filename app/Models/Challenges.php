<?php

namespace App\Models;

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
}
