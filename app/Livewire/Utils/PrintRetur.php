<?php

namespace App\Livewire\Utils;

use App\Models\Matreq;
use App\Models\Retur;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PrintRetur extends Component
{

    public Retur $retur;

    public function mount(Retur $retur) {
        $retur->loadMissing('items.farmalkes.pbf');
        $this->retur = $retur;
    }

    #[Layout('components.layouts.print')]
    public function render()
    {
        return view('livewire.utils.print-retur');
    }
}
