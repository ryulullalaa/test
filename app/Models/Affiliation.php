<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member;

class Affiliation extends Model
{
    public function member()
    {
        return $this->hasMany(Member::class);
    }
}
