<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmalkes extends Model
{
    public function pbf() {
        return $this->belongsTo(Pbf::class, 'pbf_kode', 'kode');
    }
    
    protected $casts = [
        'diskon' => 'float', // or 'decimal:2'
    ];
}
