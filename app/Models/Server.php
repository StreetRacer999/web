<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class Server extends Model
{
    use HasFactory, Auditable;

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public function machine()
    {
        return $this->belongsTo('App\Models\Machine');
    }

    public function events()
    {
        return $this->hasMany('App\Models\ServerEvent');
    }

    /**
     * Events filters
     */
    public function logs()
    {
        return $this->events()->where('type', 'log');
    }

    public function warns()
    {
        return $this->events()->where('type', 'warn');
    }

    public function errors()
    {
        return $this->events()->where('type', 'error');
    }
}
