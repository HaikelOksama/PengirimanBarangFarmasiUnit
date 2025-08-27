<?php

namespace App\Models;

use App\Services\BarangExpireService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class ObatExpired extends Model
{
    public function items() : HasMany {
        return $this->hasMany(ObatExpiredItem::class);
    }

    public function unit() : BelongsTo {
        return $this->belongsTo(Unit::class);
    }

    public function sync() : ObatExpired {
        app(BarangExpireService::class)->syncWithRemote($this);
        return $this;
    }

    public static function syncAll() {
        $unitId = Auth::user()->unit_id;

        static::where('unit_id', $unitId)->get()->each(function ($expired) {
            $expired->sync();
        });

        return true;
    }
}
