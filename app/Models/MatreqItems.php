<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatreqItems extends Model
{
    

    public function farmalkes() : BelongsTo {
        return $this->belongsTo(Farmalkes::class);
    }

    public function matreq() : BelongsTo {
        return $this->belongsTo(Matreq::class);
    }

    public function calculateSubtotal() {
        return $this->pesan * $this->farmalkes->hna;
    }
}
