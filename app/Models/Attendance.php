<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member;

class Attendance extends Model
{
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
