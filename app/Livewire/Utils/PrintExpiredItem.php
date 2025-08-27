<?php

namespace App\Livewire\Utils;

use App\Models\ObatExpired;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PrintExpiredItem extends Component
{
    public $ed;

    public function mount(ObatExpired $ed) {
        $this->ed = $ed;
    }

    #[Layout('components.layouts.print')]
    public function render()
    {
        if(!Auth::user()->hasRole('admin') && Auth::user()->unit != $this->ed->unit) {
            abort(403);
        }
        return view('livewire.utils.print-expired-item');
    }
}
