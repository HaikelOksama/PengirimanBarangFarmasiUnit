<?php

namespace App\Livewire\Component\Modal;

use App\Models\Matreq;
use App\Models\Retur;
use Livewire\Component;

class ReturListModal extends Component
{

    public Matreq $matreq;

    public function mount(Matreq $matreq) {
        $matreq->loadMissing('fromUnit', 'toUnit', 'items.farmalkes.pbf', 'retur');
        $this->matreq = $matreq;
    }

    public function delete(Retur $retur) {
        $retur->delete();
        $this->dispatch('success', 'Data berhasil dihapus');
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

    public function render()
    {
        return view('livewire.component.modal.retur-list-modal');
    }
}
