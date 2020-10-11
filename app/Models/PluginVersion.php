<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PluginVersion extends Model
{
    use HasFactory;

    public function plugin()
    {
        return $this->belongsTo('App\Models\Plugin');
    }
}
