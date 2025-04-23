<?php

namespace App\Services;

use App\Enums\MatreqStatus;
use App\Enums\MatreqType;
use App\Models\Matreq;
use App\Models\MatreqItems;
use App\Models\Unit;

class MatreqService {

    public function __construct(public Matreq $matreq) {}
    

    public function syncItems(array $items)
    {

        
    // public function calculateSubtotal() {
    //     return $this->pesan * $this->farmalkes->hna;
    // }

    // public function calculateTotal() {
    //     return $this->calculateSubtotal() - ($this->calculateSubtotal() * $this->farmalkes->diskon / 100);
    // }
        $matchedIds = [];
        foreach ($items as $itemData) {
            $farmalkes = $itemData['data'];
            $qty = $itemData['qty'];
    
            // Try to update existing item for this matreq and farmalkes_id
            $matreqItem = MatreqItems::updateOrCreate(
                [
                    'matreq_id' => $this->matreq->id,
                    'farmalkes_id' => $farmalkes->id,
                ],
                [
                    'matreq_id' => $this->matreq->id,
                    'farmalkes_id' => $farmalkes->id,
                    'pesan' => $qty,
                    'subtotal_harga' => $farmalkes->hna * $qty,
                    'total_harga' => ($farmalkes->hna * $qty) - ($farmalkes->hna * $qty * $farmalkes->diskon / 100),
                    'diskon' => $farmalkes->diskon,
                    'hna' => $farmalkes->hna,
                    'harga_beli' => $farmalkes->harga_beli,
                    'isi' => $farmalkes->isi
                ]
            );
            
            $matchedIds[] = $matreqItem->id;
        }
    
        // Optional: Delete matreq items that were not included in the sync
        $this->matreq->items()
            ->whereNotIn('id', $matchedIds)
            ->delete();
    }

    public function requestMatreq() {
        $code = self::generateMatreqNum($this->matreq, MatreqType::REQUEST);
        $this->matreq->matreq_no = $code;
        $this->matreq->status = MatreqStatus::REQUEST->value;
        $this->matreq->save();
    }

    public function sendMatreq() {
        $code = self::generateMatreqNum($this->matreq, MatreqType::KIRIM);
        $this->matreq->kirim_no = $code;
        $this->matreq->status = MatreqStatus::KIRIM->value;
        $this->matreq->tgl_kirim = now();
        $this->matreq->save();
    }

    public function sendMatreqItem(MatreqItems $matreqItems, int $nominalKirim) {
        $matreqItems->kirim = $nominalKirim;
        $matreqItems->subtotal_harga = $matreqItems->calculateSubtotal();
        $matreqItems->save();
    }

    public static function generateMatreqNum(Matreq $matreq, MatreqType $type) {
        $now = now(); // or Carbon::now()
        $month = $now->format('m');
        $year = $now->format('Y');
    
        // Count existing matreqs in this month & year
        $count = Matreq::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('to_unit_id', $matreq->to_unit_id)
            ->count() + 1;
    
        $number = str_pad($count, 5, '0', STR_PAD_LEFT); // 00001, 00002, etc.
        if ($type == MatreqType::KIRIM) {
            return "KRU/{$matreq->toUnit->code}/{$number}{$month}{$year}";
        }else {
            return "MRU/{$matreq->fromUnit->code}/{$number}-{$month}{$year}";
        }
    }
}