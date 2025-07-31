<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturItem extends Model
{
    public function retur() : BelongsTo {
        return $this->belongsTo(Retur::class, 'returs_id', 'id');
    }

    public function farmalkes() : BelongsTo {
        return $this->belongsTo(Farmalkes::class, 'farmalkes_id', 'id');
    }
}
