<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Affiliation;

class Member extends Model
{
    public function affiliation()
    {
        return $this->belongsTo(Affiliation::class);
    }
}