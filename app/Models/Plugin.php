<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    use HasFactory;

    public function developer()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function versions()
    {
        return $this->hasMany('App\Models\PluginVersion');
    }
}
