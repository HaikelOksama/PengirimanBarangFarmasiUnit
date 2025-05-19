<?php

namespace App\Livewire\Component\Modal;

use App\Enums\MatreqStatus;
use App\Models\Farmalkes;
use App\Models\Matreq;
use App\Models\MatreqItems;
use App\Services\MatreqService;
use Arr;
use Auth;
use DB;
use Livewire\Component;

class MatreqModal extends Component
{

    public ?Matreq $matreq;

    protected MatreqService $service;


    public $searchFarmalkes = '';
    public $selected = null;

    
    public $items = [];

    public function mount(Matreq $matreq) {
        $this->matreq = $matreq->loadMissing('fromUnit', 'toUnit', 'items.farmalkes.pbf');
        $this->items = Arr::mapWithKeys($matreq->items->select('id','pesan', 'kirim', 'subtotal_harga', 'total_harga', 'hna', 'diskon')->toArray(), function($item) {
            return [$item['id'] => $item];
        });
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <!-- Loading spinner... -->
            <flux:button class="w-30">
                <span class="loading loading-bars loading-xl"></span>
            </flux:button>
            
        </div>
        HTML;
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
                ->select('id', 'nama', 'pbf_kode')->get();
                // dd($options);
        }
        return view('livewire.component.modal.matreq-modal', compact('options'));
    }

    public function save() {
        if ($this->matreq) {
            // dd($this->items);
            DB::transaction(function () {
                foreach ($this->items as $key => $item) {
                    $itemD = MatreqItems::with('farmalkes.pbf')->where('id', $item['id'])->limit(1)->first();
                    // dd($itemD->first()->loadMissing('farmalkes'));
                    $itemD->update([
                        'pesan' => $item['pesan'],
                        'kirim' => $item['kirim'],
                    ]);
                    if(Auth::user()->hasRole('admin')) {
                        $itemD->update([
                            'hna' => $item['hna'],
                            'diskon' => $item['diskon'],
                        ]);
                        $farmalkes = $itemD->farmalkes;
                        if($itemD->hna != $farmalkes->hna || $itemD->diskon != $farmalkes->diskon) {
                            $farmalkes->hna = $itemD->hna;
                            $farmalkes->diskon = $itemD->diskon;
                            $farmalkes->save();
                        }
                    }
                    $itemD->updateSubtotal();
                }
            });
        }
        $this->dispatch('success', 'Data berhasil disimpan');
    }

    public function send() {
        if($this->matreq) {
            $this->service->sendMatreq();
            $this->dispatch('info', 'Permintaan untuk unit ' . $this->matreq->toUnit->nama . '  dikirim');
        }
    }

    public function addFarmalkes() {
        $this->validate([
            'selected' => ['required', 'exists:farmalkes,id']
        ]);

        $farmalkes = Farmalkes::find($this->selected);
        $this->selected = null;
        $this->searchFarmalkes = '';
        $this->matreq->items()->create([
            'pesan' => 1,
            'kirim' => 0,
            'farmalkes_id' => $farmalkes->id,
            'hna' => $farmalkes->hna,
            'diskon' => $farmalkes->diskon,
            'harga_beli' => $farmalkes->harga_beli,
            'subtotal_harga' => $farmalkes->hna ,
            'total_harga' => $farmalkes->hna + ($farmalkes->hna * $farmalkes->diskon / 100),
            'isi' => $farmalkes->isi,
        ]);

        $this->items = Arr::mapWithKeys($this->matreq->items->select('id','pesan', 'kirim', 'subtotal_harga', 'total_harga', 'hna', 'diskon')->toArray(), function($item) {
            return [$item['id'] => $item];
        });
        $this->dispatch('success', 'Item berhasil ditambahkan');
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
