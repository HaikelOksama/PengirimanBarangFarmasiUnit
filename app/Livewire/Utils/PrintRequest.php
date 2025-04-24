<?php

namespace App\Livewire\Utils;

use App\Models\Matreq;
use Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PrintRequest extends Component
{
    public Matreq $matreq;

    public function mount(Matreq $matreq) {
        $matreq->loadMissing('fromUnit', 'toUnit', 'items.farmalkes.pbf');
        $this->matreq = $matreq;
    }

    #[Layout('components.layouts.print')]
    public function render()
    {
        if(!Auth::user()->hasRole('admin') && Auth::user()->unit != $this->matreq->fromUnit || $this->matreq->status == \App\Enums\MatreqStatus::REQUEST->value) {
            abort(403);
        }
        
        return view('livewire.utils.print-request');
    }
}
