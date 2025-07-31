<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matreq extends Model
{

    protected $casts = [
        'tgl_kirim' => 'datetime',
        'tgl_terima' => 'datetime',
    ];

    public function fromUnit() : BelongsTo {
        return $this->belongsTo(Unit::class);
    }

    public function toUnit() : BelongsTo {
        return $this->belongsTo(Unit::class);
    }

    public function items() : HasMany {
        return $this->hasMany(MatreqItems::class);
    }

    public function retur() : HasMany {
        return $this->hasMany(Retur::class, 'matreqs_id', 'id');
    }

    public function calculateSubtotal() {
        return $this->items()->sum('subtotal_harga');
    }

    public function calculateTotal() {
        return $this->items()->sum('total_harga');
    }



    #[Scope()]
    public function search(Builder $query, $searchQ) {
        return $query->where('status', 'like', "%{$searchQ}%")
        ->orWhere('matreq_no', 'like', "%{$searchQ}%")
        ->orWhere('kirim_no', 'like', "%{$searchQ}%");
    }

    #[Scope()]
    public function dateRange(Builder $query, $start, $end) {
        return $query->whereBetween('created_at', [$start, $end]);
    }
}
