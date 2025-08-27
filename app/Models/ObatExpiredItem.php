<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObatExpiredItem extends Model
{
    public function obatExpired() : BelongsTo {
        return $this->belongsTo(ObatExpired::class);
    }

    public function farmalkes() : BelongsTo {
        return $this->belongsTo(Farmalkes::class);
    }
}
