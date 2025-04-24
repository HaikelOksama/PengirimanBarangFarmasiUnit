<?php

namespace App\Livewire\Partials;

use App\Enums\MatreqStatus;
use App\Models\Matreq;
use Auth;
use Livewire\Component;

class Sidebar extends Component
{
    public function render()
    {
        if(Auth::user()->unit) {
            $kirimRequest = Matreq::where('to_unit_id', Auth::user()->unit->id)->where('status', MatreqStatus::REQUEST->value)->count();
            $kirimMasuk = Matreq::where('from_unit_id', Auth::user()->unit->id)->where('status', MatreqStatus::KIRIM->value)->count();
        } else {
            $kirimRequest = 0;
            $kirimMasuk = 0;
        }
        
        return view('livewire.partials.sidebar', compact('kirimRequest', 'kirimMasuk'));
    }
}
