<?php

namespace App\Livewire\Component\Modal;

use App\Enums\MatreqStatus;
use App\Models\Farmalkes;
use App\Models\Matreq;
use App\Models\MatreqItems;
use App\Services\MatreqService;
use Arr;
use Livewire\Component;

class MatreqModal extends Component
{

    public ?Matreq $matreq;

    protected MatreqService $service;


    public $searchFarmalkes = '';
    public $selected = null;

    
    public $items = [];

    public function mount(Matreq $matreq) {
        $matreqs = $matreq->loadMissing('fromUnit', 'toUnit', 'items.farmalkes.pbf');
        $this->matreq = $matreqs;
        $this->items = Arr::mapWithKeys($matreq->items->select('id','pesan', 'kirim', 'subtotal_harga', 'total_harga', 'hna')->toArray(), function($item) {
            return [$item['id'] => $item];
        });
    }

    public function removeItem($id) {
        unset($this->items[$id]);
        $this->matreq->items()->where('id', $id)->delete();
        $this->dispatch('success', 'Data berhasil dihapus');
    }

    public function boot() {
        $this->service = new MatreqService($this->matreq);
    }

    public function render()
    {
        $options = [];
        if (strlen($this->searchFarmalkes) >= 2) {
            $options = Farmalkes::with('pbf')->where('nama', 'like', '%' . $this->searchFarmalkes . '%')
                ->limit(5)
                ->pluck('nama', 'id');
        }
        return view('livewire.component.modal.matreq-modal', compact('options'));
    }

    public function save() {
        if ($this->matreq) {
            foreach ($this->items as $key => $item) {
                $itemD = $this->matreq->items()->where('id', $item['id'])->limit(1);
                $itemD->update([
                    'pesan' => $item['pesan'],
                    'kirim' => $item['kirim'],
                ]);
                $itemD->first()->updateSubtotal();
            }
        }
        $this->dispatch('success', 'Data berhasil disimpan');
    }

    public function send() {
        if($this->matreq) {
            $this->service->sendMatreq();
            $this->dispatch('info', 'Permintaan untuk unit ' . $this->matreq->toUnit->nama . '  dikirim');
        }
    }

    public function changeFarmalkes($id) {
        $current = Farmalkes::find($id);
        $new = Farmalkes::find($this->selected);
        $matreqItem = MatreqItems::where('matreq_id', $this->matreq->id)->where('farmalkes_id', $current->id)->first();
        $matreqItem->farmalkes_id = $new->id;
        $matreqItem->hna = $new->hna;
        $matreqItem->diskon = $new->diskon;
        $matreqItem->harga_beli = $new->harga_beli;
        $matreqItem->isi = $new->isi;
        $matreqItem->save();
        $matreqItem->updateSubtotal();
        $this->reset('selected', 'searchFarmalkes');
        $this->dispatch('success', 'Data berhasil diganti');
    }
}
