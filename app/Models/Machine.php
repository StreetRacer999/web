<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class Machine extends Model
{
    use HasFactory, Auditable;

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public function servers()
    {
        return $this->hasMany('App\Models\Server');
    }
}
