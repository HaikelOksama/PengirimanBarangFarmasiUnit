<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matreq extends Model
{
    public function fromUnit() : BelongsTo {
        return $this->belongsTo(Unit::class);
    }

    public function toUnit() : BelongsTo {
        return $this->belongsTo(Unit::class);
    }

    public function items() : HasMany {
        return $this->hasMany(MatreqItems::class);
    }
}
