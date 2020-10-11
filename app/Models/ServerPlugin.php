<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerPlugin extends Model
{
    use HasFactory;

    public function server()
    {
        return $this->belongTo('App\Models\Server');
    }

    public function plugin()
    {
        return $this->belongsTo('App\Models\PluginVersion', 'plugin_version_id');
    }
}
