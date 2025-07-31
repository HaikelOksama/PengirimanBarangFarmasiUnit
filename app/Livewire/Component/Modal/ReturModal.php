<?php

namespace App\Livewire\Component\Modal;

use App\Models\Farmalkes;
use App\Models\Matreq;
use App\Models\Retur;
use App\Services\MatreqService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class ReturModal extends Component
{
    public ?Matreq $matreq;

    protected MatreqService $service;
    private ?Retur $retur;
    public $items = [];
    
    public $returItems = [];
    public $tglRetur;
    public $keterangan;

    public function rules()
    {
        return [
            'tglRetur' => 'required|date',
            'keterangan' => 'required',
            'returItems.*.qty' => ['required', 'numeric', 'min:1'],
        ];
    }

    public function mount(Matreq $matreq)
    {
        $this->matreq = $matreq->loadMissing('fromUnit', 'toUnit', 'items.farmalkes.pbf');
        $this->items = Arr::mapWithKeys($matreq->items->withRelationshipAutoloading()->select('id', 'farmalkes_id', 'pesan', 'kirim', 'subtotal_harga', 'total_harga', 'hna', 'diskon')->toArray(), function ($item) {
            return [$item['id'] => $item];
        });
    }

    public function boot()
    {
        $this->service = new MatreqService($this->matreq);
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

    public function addReturItem($id)
    {
        // Prevent duplicate item by checking if it already exists by key
        if (isset($this->returItems[$id])) {
            return;
        }

        // Find the item and eager load related model
        $item = $this->matreq->items()->find($id);
        if (!$item) {
            return; // Handle not found gracefully
        }

        $item->load('farmalkes');

        // Store the item in the array using item ID as key
        $this->returItems[$id] = [
            'item' => $item,
            'qty' => 1,
        ];
    }

    public function removeReturItem($id)
    {
        unset($this->returItems[$id]);
    }

    public function submitRetur() {
        // dd($this->returItems);
        $validator = $this->validateQty();

        if (!$validator->fails()) {
            $this->service->retur($this->returItems, $this->tglRetur, $this->keterangan);
            $this->dispatch('success', 'Retur berhasil dikirim');
            $this->reset('returItems', 'tglRetur', 'keterangan');
            $this->dispatch('refresh-list')->to(ReturListModal::class);
        }
    }

    public function render()
    {
        return view('livewire.component.modal.retur-modal');
    }

    private function validateQty() {
        $baseRules = $this->rules();

        // Perform base validation first
        $this->validate($baseRules);
    
        // Build a new validator for dynamic max rules
        $validator = Validator::make(
            ['returItems' => $this->returItems],
            [], // We'll validate manually below
        );
    
        foreach ($this->returItems as $itemId => $data) {
            $max = $this->items[$itemId]['kirim'] ?? 0;
            // dd($max);
            $validator->after(function ($validator) use ($data, $itemId, $max) {
                if ($data['qty'] > $max) {
                    $validator->errors()->add("returItems.$itemId.qty", "Jumlah retur tidak boleh melebihi jumlah dikirim (max: $max).");
                }
            });
        }
    
        if ($validator->fails()) {
            $this->setErrorBag($validator->errors());
        }

        return $validator;
    }
}
