<?php

namespace App\Models;

use DB;
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
        if($this->kirim != 0) {
            return $this->kirim * $this->hna;
        }
        return $this->pesan * $this->hna;
    }

    public function calculateTotal() {
        return $this->calculateSubtotal() - ($this->calculateSubtotal() * $this->diskon / 100);
    }

    public function updateSubtotal() {
            $this->subtotal_harga = $this->calculateSubtotal();
            $this->total_harga = $this->calculateTotal();
            
            $this->save();
    }
}
