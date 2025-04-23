<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pbf extends Model
{
    
    public function farmalkes()
    {
        return $this->hasMany(Farmalkes::class);
    }
}
