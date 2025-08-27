<?php

namespace App\Livewire\Component;

use App\Models\ObatExpired;
use Livewire\Component;

class ObatExpiredDetailModal extends Component
{
    public $ed;

    public function mount(ObatExpired $ed) {
        $this->ed = $ed;
    }

    public function render()
    {
        return view('livewire.component.obat-expired-detail-modal');
    }
    
}
