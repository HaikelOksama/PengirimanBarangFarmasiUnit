<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Retur extends Model
{
    public function matreq() : BelongsTo {
        return $this->belongsTo(Matreq::class, 'matreqs_id', 'id');
    }

    public function items() : HasMany {
        return $this->hasMany(ReturItem::class, 'returs_id', 'id');
    }
}
