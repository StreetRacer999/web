<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerEvent extends Model
{
    use HasFactory;

    public $guarded = [];

    public function server()
    {
        return $this->belongsTo('App\Models\Server');
    }
}
